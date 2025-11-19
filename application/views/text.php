<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Formatted Excel Export (Masterfile)</title>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<style>
  body { font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background:#f3f4f6; padding:24px; }
  .container { max-width:1100px; margin:0 auto; }
  h1 { font-size:20px; margin-bottom:8px; }
  textarea { width:100%; height:380px; padding:12px; border-radius:8px; border:1px solid #d1d5db; font-family:monospace; font-size:13px; background:white; }
  .controls { display:flex; gap:8px; margin-top:12px; align-items:center; }
  button { padding:10px 14px; border-radius:8px; border:0; cursor:pointer; background:#0ea5e9; color:white; font-weight:600; }
  button.secondary { background:#374151; }
  p.note { color:#374151; margin-top:10px; }
</style>
</head>
<body>
<div class="container">
  <h1>Masterfile formatted Excel exporter</h1>
  <p class="note">Edit textarea content if needed. The script will find the first large pipe (`|`) table and use its rows to populate the formatted Masterfile sheet.</p>

  <textarea id="sourceText">
**

PARTS: VESSEL (SHELL & ELLIPSOIDAL HEAD)
PHASE:
FLUID: HOT WATER
TYPE: HORIZONTAL (VESSEL)
SPECIFICATION: ASME SECTION VIII, DIV. 1, ID 2017
GRADE: ASTM A283 GR. C (Shell/Head); Nozzles: ASTM A312 TP304L / ASTM A182 F304L
OPERATING TEMPERATURE: 90 °C
OPERATING PRESSURE: 5 Barg
DESIGN TEMPERATURE: 120 °C
DESIGN PRESSURE: 6 Barg
    </textarea>

  <div class="controls">
    <button id="btnDownload">Download Excel (formatted)</button>
    <button id="btnPreview" class="secondary">Preview parsed table (console)</button>
  </div>
  <p class="note">Note: the script builds a Masterfile-like spreadsheet with merged yellow headers and fills rows from the first large pipe table found in the textarea.</p>
</div>

<script>
/*
  Approach:
  1. Find the largest contiguous block of lines that contain '|' (a Markdown table).
  2. Parse it into rows (strip leading/trailing '|' and whitespace).
  3. Build a formatted Excel workbook (SheetJS) with:
     - Merged multi-row header styled in yellow
     - Column widths and borders
     - Data rows filled from parsed table (mapping columns to Masterfile columns)
  4. Trigger download.
*/

// Utility: find largest pipe-table block
function findLargestPipeTable(text) {
  const lines = text.split(/\r?\n/);
  let blocks = [];
  let current = [];
  for (let line of lines) {
    if (line.includes("|")) {
      current.push(line);
    } else {
      if (current.length) { blocks.push(current); current = []; }
    }
  }
  if (current.length) blocks.push(current);
  // choose block with max length
  if (!blocks.length) return null;
  blocks.sort((a,b) => b.length - a.length);
  return blocks[0].join("\n");
}

// Utility: parse markdown table block into array of arrays
function parseMarkdownTableBlock(block) {
  const rows = block.split(/\r?\n/).map(r => r.trim()).filter(r => r.length>0);
  const parsed = [];
  for (let r of rows) {
    // skip purely separator rows like | :--- | :---: |
    const rowWithoutPipes = r;
    const cells = rowWithoutPipes.split("|").map(c => c.trim());
    // remove empty leading/trailing cells produced by split on pipes
    if (cells[0] === "") cells.shift();
    if (cells[cells.length-1] === "") cells.pop();
    // check if it's an alignment row
    const isAlignRow = cells.every(c=>/^:?-+:?$/.test(c) || c==="");
    if (isAlignRow) continue;
    parsed.push(cells);
  }
  return parsed;
}

// Styling helper (SheetJS uses .s for style objects)
function makeCellStyle({bold=false, align='center', valign='center', bg=null, wrap=false, border=true, numFmt=null}={}) {
  const style = {};
  if (bold) style.font = { bold: true, sz: 10, name: "Calibri" };
  else style.font = { sz: 10, name: "Calibri" };
  style.alignment = { horizontal: align, vertical: valign, wrapText: !!wrap };
  if (bg) style.fill = { patternType: "solid", fgColor: { rgb: bg.replace('#','').toUpperCase() } };
  if (border) {
    style.border = {
      top: { style: "thin", color: { auto: 1 } },
      bottom: { style: "thin", color: { auto: 1 } },
      left: { style: "thin", color: { auto: 1 } },
      right: { style: "thin", color: { auto: 1 } }
    };
  }
  if (numFmt) style.numFmt = numFmt;
  return style;
}
function extractSpecifications(text) {
  // Helper regex function
  function pick(regex) {
    const m = text.match(regex);
    return m ? m[1].trim() : "";
  }

  // Extract raw values
  let designTemp  = pick(/DESIGN TEMPERATURE\s*\|\s*([0-9.]+)\s*C/i);
  let workingTemp = pick(/WORKING TEMPERATURE\s*\|\s*([0-9.]+)\s*C/i);
  let workingPress = pick(/WORKING PRESSURE\s*\|\s*([0-9.]+)\s*KPaG/i);

  // Convert pressure KPaG → MPa (Excel expects MPa)
  let workingPressMPa = workingPress ? (parseFloat(workingPress) / 1000).toFixed(3) : "";

  return {
    designTemp,
    designPress: workingPressMPa,
    operatingTemp: workingTemp,
    operatingPress: workingPressMPa
  };
}

// --- Simplified Insulation Extractor ---

function extractInsulation(parsedTable) {
  if (!parsedTable) return "NO";  // default

  for (let row of parsedTable) {
    // find column containing "INSULATION" or "INSULATIONS"
    const idx = row.findIndex(c => /INSULATIONS?|INSULATION/i.test(c));
    if (idx >= 0) {
      // check if next column has a value (non-empty, not N/A)
      if (row[idx + 1] && row[idx + 1].trim() !== "" && row[idx + 1].toUpperCase() !== "N/A") {
        return "YES";
      }
      // fallback: if current cell exists but next is empty, still count as YES
      return "YES";
    }
  }

  return "NO";  // if nothing found
}

function extractKeyValues(text) {
  function pick(label) {
    const regex = new RegExp(label + "\\s*:\\s*(.*)", "i");
    const m = text.match(regex);
    return m ? m[1].trim() : "";
  }

  return {
    parts: pick("PARTS"),
    phase: pick("PHASE"),
    fluid: pick("FLUID"),
    type: pick("TYPE"),
    spec: pick("SPECIFICATION"),
    grade: pick("GRADE"),
    opTemp: pick("OPERATING TEMPERATURE"),
    opPress: pick("OPERATING PRESSURE"),
    desTemp: pick("DESIGN TEMPERATURE"),
    desPress: pick("DESIGN PRESSURE")
  };
}



// Core workbook builder (update: now inserts SOURCE TEXT sheet)
function buildMasterfileWorkbook(parsedTable, specs, sourceText) {
  const insertedPartsSet = new Set();
  const partsKeywords = ["Tube Bundle","Shell","Channel","Head","Bottom Head","Top Head"];

  const aoa = [];

  // --- header rows ---
  const headerRow1 = ["NO.", "EQUIPMENT NO.", "PMT NO.", "EQUIPMENT DESCRIPTION", "PARTS", "PHASE", "FLUID", "MATERIAL ", "", "", "INSULATION", "DESIGN", "", "OPERATING", ""];
  const headerRow2 = ["", "", "", "", "", "", "", "TYPE", "SPEC.", "GRADE", "(yes/No)", "TEMP. (°C)", "PRESSURE (Mpa)", "TEMP. (°C)", "PRESSURE (Mpa)"];
  const headerRow3 = Array(15).fill("");
  aoa.push(headerRow1, headerRow2, headerRow3);

  // If no table found, still output specs
if (!parsedTable || !parsedTable.length) {

    const kv = extractKeyValues(sourceText);  // <— NEW

    const row = Array(15).fill("");

    row[0] = 1;
    row[4] = kv.parts;
    row[5] = kv.phase;
    row[6] = kv.fluid;

    row[7] = kv.type;   // MATERIAL → TYPE
    row[8] = kv.spec;   // MATERIAL → SPEC
    row[9] = kv.grade;  // MATERIAL → GRADE

    row[10] = "NO";

    row[11] = kv.desTemp;
    row[12] = kv.desPress;
    row[13] = kv.opTemp;
    row[14] = kv.opPress;

    aoa.push(row);

    // create workbook
    const ws = XLSX.utils.aoa_to_sheet(aoa);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Masterfile");

    // source text tab
    const ws2 = XLSX.utils.aoa_to_sheet([[sourceText]]);
    ws2["!cols"] = [{ wch: 150 }];
    XLSX.utils.book_append_sheet(wb, ws2, "Source Text");

    return wb;
}


  // Normal table
  const dataRows = [];
  const insulationValue = extractInsulation(parsedTable);

  let insulationSet = false;

  for (let i = 0; i < parsedTable.length; i++) {
    const rowCells = parsedTable[i];
    const row = Array(15).fill("");

    row[0] = i + 1;
    row[1] = rowCells[0] || "";
    row[2] = rowCells[1] || "";
    row[3] = rowCells[2] || "";
    row[4] = rowCells[3] || "";

    if (!insulationSet && insulationValue === "YES") {
      row[10] = "YES";
      insulationSet = true;
    }

    row[11] = specs.designTemp;
    row[12] = specs.designPress;
    row[13] = specs.operatingTemp;
    row[14] = specs.operatingPress;

    dataRows.push(row);
  }

  for (let r of dataRows) aoa.push(r);

  // Build workbook
  const ws = XLSX.utils.aoa_to_sheet(aoa);
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, "Masterfile");

  // ➜ ADD SOURCE TEXT SHEET
  const ws2 = XLSX.utils.aoa_to_sheet([[sourceText]]);
  ws2["!cols"] = [{ wch: 150 }];
  XLSX.utils.book_append_sheet(wb, ws2, "Source Text");

  return wb;
}





// Download handler
document.getElementById("btnDownload").addEventListener("click", function() {
  const text = document.getElementById("sourceText").value;

  const block = findLargestPipeTable(text);
  let parsed = null;
  if (block) parsed = parseMarkdownTableBlock(block);

  const specs = extractSpecifications(text);

  // IMPORTANT → pass source text
  const wb = buildMasterfileWorkbook(parsed, specs, text);

  XLSX.writeFile(wb, "Masterfile_Formatted.xlsx");
});


// Preview parsed table to console
document.getElementById("btnPreview").addEventListener("click", function() {
  const text = document.getElementById("sourceText").value;
  const block = findLargestPipeTable(text);
  if (!block) {
    console.log("No pipe-table found in textarea.");
    alert("No pipe-table found — check the textarea content.");
    return;
  }
  const parsed = parseMarkdownTableBlock(block);
  console.log("Parsed table:", parsed);
  alert("Parsed table printed to console (open DevTools -> Console).");
});
</script>
</body>
</html>
