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
## Drawing Notes and Title Block

**[Top Center/Revision]**
Approved For Construction
[Signature/Stamp]
3/10/2018

**NOTES**

| No. | DATE | BY | AMENDMENTS |
| :--- | :--- | :--- | :--- |
| 1. | All dimension in mm, unless otherwise stated. | | |
| 2. | Top torispherical head shall include 35mm wide border line. | | |
| 3. | DWG (F) tapped BSPT telltale hole shall be provided on all reinforced pad and saddle | | |
| | and plug with a minimum of 3mm. | | |
| 4. | All fillet weld and nozzle to shell weld shall be foam/TIG/ARC process. | | |
| 5. | After completion of the lightening test, hard grease shall be applied to telltale hole. | | |
| | [Stamp: WIRED INTERNATIONAL] | | |
| | [Stamp: PRESSURE VESSEL] | | |

---

## Tables (Right Side)

**NOZZLE**

| NOZZLE | NOZZLE SIZE | NOZZLE SCH. | QTY. | FLANGE'S RATING | FLANGE'S TYPE | FUNCTION OF NOZZLE |
| :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| N18 | Ø450 | PLATE | 1 | PN16 | SUP ON | MANHOLE |
| N17 | Ø50 | 40 | 1 | PN16 | SUP ON | STEAM INLET |
| N16 | Ø50 | 40 | 1 | PN16 | SUP ON | DEGASIFIED WATER OUTLET |
| N15 | Ø50 | 40 | 1 | PN16 | SUP ON | OVERFLOW |
| N14 | Ø40 | 40 | 1 | PN16 | SUP ON | WATER INLET |
| N13 | Ø50 | 80 | 1 | PN16 | SUP ON | DRAIN |
| N12 | Ø50 | 80 | 1 | PN16 | SUP ON | CONDENSATE RETURN |
| N11 | Ø50 | 80 | 1 | PN16 | SUP ON | FROM ECONOMIZER |
| N10 | Ø50 | 80 | 1 | PN16 | SUP ON | HIGH LEVEL |
| N9 | Ø80 | 80 | 2 | SOCKET | BSPT | HIGH LEVEL |
| N8 | Ø25 | 80 | 1 | SOCKET | BSPT | TEMP. TRANSMITTER |
| N7 | Ø25 | 80 | 1 | SOCKET | BSPT | TEMPERATURE GAUGE |
| N6 | Ø25 | - | 1 | SOCKET | BSPT | SCRUBBER DRAIN |
| N5 | Ø25 | - | 1 | SOCKET | BSPT | AIR VENT |
| N4 | Ø15 | - | 1 | SOCKET | BSPT | AIR VENT |
| N3 | Ø15 | - | 1 | SOCKET | BSPT | VACUUM BREAKER |
| N2 | Ø15 | - | 1 | SOCKET | BSPT | PRESSURE TRANSMITTER |
| N1 | Ø15 | - | 1 | SOCKET | BSPT | PRESSURE GAUGE |

**MATERIAL**

| | |
| :--- | :--- |
| SHELL & DISHED END | SA 285 GR.C |
| FLANGE, MANHOLE FLANGE AND COVER | C22.8 & S355JR (N1X) |
| PIPE | SA 106 GR.B & SA 312 TP 304 (N1X) |
| SOCKET | SA 105 & SA 350 LF2 |
| REINFORCED PLATE | SA 283 GR.C |
| SADDLES | S275JR |
| PRESSURE RETAINING | SA 193 B7 / SA 194 2H |
| BOLT & NUT | EXTERNAL FITTINGS | JIS G3507 |

**SPECIFICATION**

| | |
| :--- | :--- |
| DESIGN CODE | ASME SEC. VIII DIV 1 2015 |
| WORKING PRESSURE | 350 KPaG |
| DESIGN TEMPERATURE | 155 C |
| HYDROSTATIC TEST PRESSURE | 455 KPaG |
| WORKING TEMPERATURE | 150 C |
| DESIGN TEMPERATURE | 155 C |
| MDMT | 0 C |
| MEDIUM OF SERVICE | WATER & STEAM |
| HEAD | NIL |
| SHELL & DISHED END | NIL |
| JOINT EFFICIENCY | HEAD 0.7 (WELD UW-12) |
| | SHELL 0.7 (WELD UW-12) |
| CORROSION ALLOWANCE | FOR HEAD & SHELL | 1 mm |
| | FOR NOZZLE | 0 mm |
| CAPACITY | 6.11 m3 |
| DEPARTMENT OF OCCUPATIONAL SAFETY AND HEALTH MALAYSIA, CHOP & SIGNATURE | |

---

## Dimensional Tables (Bottom)

**DIMENSION OF NOZZLE**

| NO. | NOMINAL SIZE | OUTSIDE DIA. | THICKNESS (T) | H | R | MATERIAL |
| :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| #20 | 42.4 | 20.95 | 5.54 MM | 10 MM | SA.106 GR.B SCH.80 PIPE |
| #40 | 48.3 | 20.95 | 5.54 MM | 10 MM | SA.106 GR.B SCH.80 PIPE |
| #50 | 60.3 | 5.54 MM | 10 MM | SA.106 GR.B SCH.80 PIPE |
| #80 | 88.9 | 5.54 MM | 10 MM | SA.106 GR.B SCH.80 PIPE |
| #100 | 108.3 | 7.11 MM | 15 MM | SA.106 GR.B SCH.40 PIPE |
| #150 | 159.0 | 7.11 MM | 15 MM | SA.106 GR.B SCH.40 PIPE |

**BS EN 1092-1:2007 PN16**

| NOMINAL SIZE | OUTSIDE DIAMETER | B.P.C.D. | THICKNESS T1 | H | HOLE DIA. | BOLT SIZE | NO. OF BOLT |
| :---: | :---: | :---: | :---: | :---: | :---: | :---: | :---: |
| #50 | 165 | 125 | 14 | 14 | M12 | 4 |
| #65 | 185 | 145 | 16 | 16 | M16 | 4 |
| #80 | 200 | 160 | 18 | 18 | M16 | 8 |
| #100 | 220 | 180 | 20 | 20 | M16 | 8 |
| #125 | 250 | 210 | 24 | 22 | M20 | 8 |
| #150 | 285 | 240 | 24 | 22 | M20 | 8 |

---

## General Drawing Text and Details

**[Main View Specifications]**
TORISPERICAL HEAD:
O.D. = 1600mm
NOM. THK. = 6.0mm
MIN. THK. = 5.0mm
KR = 1200mm
IR = 160mm
SF = 25mm

LW1
LW2
MS MOVABLE SADDLE
MS FIXED SADDLE
3040 SL

**DETAIL OF SPRAYBOX**
SCALE 1:1

**DETAIL OF STEAM SCRUBBER**
SCALE 1:1

**LIFTING LUG DETAIL**
SCALE 1:3

**DETAIL OF SPRAY CHAMBER**
SCALE 1:1

**DETAIL OF MOVABLE SADDLE**
SCALE 1:1

**DETAIL OF FIX SADDLE**
SCALE 1:1

**DETAIL OF Ø15 SOCKET (N1) (N2)**
SCALE 1:8
UW-16.1(y-2)
(N3)

**DETAIL OF Ø15 SOCKET (N4)**
SCALE 1:8
UW-16.1(y-2)

**DETAIL OF Ø25 SOCKET (N6)**
SCALE 1:8
UW-16.1(y-2)

**DETAIL OF Ø25 SOCKET (N5)**
SCALE 1:8
UW-16.1(y-2)

**DETAIL OF Ø25 SOCKET (N7) (N8)**
SCALE 1:6
UW-16.1(y-2)

**DETAIL OF OVERFLOW NOZZLE (N15)**
SCALE 1:2
BS 4504 PN16
UW-16.1(y-2)

**DETAIL OF Ø450 MANHOLE (N18)**
SCALE 1:4
BS 4504 PN16
UW-16.1(y-2)
FIG. 2-4(3a)

**DETAIL OF FLANGE WITHOUT REINFORCED PLATE**
SCALE 1:2
BS 4504 PN16
UW-16.1(y-2)
FIG. 2-4(3a)
(N10) (N11) (N12)

**DETAIL OF FLANGE WITH REINFORCED PLATE (N16)**
SCALE 1:2
BS 4504 PN16
UW-16.1(y-2)
FIG. 2-4(3a)

**DETAIL OF FLANGE WITH REINFORCED PLATE (N17)**
SCALE 1:2
BS 4504 PN16
UW-16.1(y-2)
FIG. 2-4(3a)

**DETAIL OF LEVEL CHAMBER NOZZLE (N9)**
SCALE 1:2
BS 4504 PN16
UW-16.1(y-2)
FIG. 2-4(3a)

**DETAIL OF HINGE**
SCALE 1:3

**DETAIL OF CIRCUMFERENTIAL & LONGITUDINAL BUTT WELD**
SCALE 1:1
UW-30001
OUTSIDE
INSIDE
PREPARATION FOR SMA & FCAW:
MJE-A-UW32(5R)-W-3 - SMAW
MJE-A-UW32(5R)-W-8 - FCAW
(SECOND SIDE CHIPPED OUT TO SOUND METAL)

---

## Title Block (Bottom Right)

**TITLE:** GENERAL DETAIL OF THERMAL DEAERATOR
       Ø1,600mm OD x 3,040mm SL x 350 KPaG

**CLIENT:** STOCK

**MANUFACTURER:**
MERGE JATI ENGINEERING SDN. BHD.
                      (CO. REG. NO.: 100244-A)
Lot 2623, Jln. Kalvali 32/119, Tel: 603-90757260
Off. Lerkas Cheras, Batu 9, Fax: 603-90757266
Cheras 32, 43200 Kajang, E-mail: merge_jati@yahoo.com.my

| DESIGNED BY: | RAYM | CHECKED BY: | RAYM |
| :--- | :--- | :--- | :--- |
| DRAWN BY: | RAYM | APPROVED BY: | CHENG |
| DATE: | AUG. 2018 | SCALE: | AS SHOWN |
| FILE NAME: | TD1600 | PROJECT CODE: | TDTW1805 |
| DRAWING NO.: | MJE/TD1600/3040/18 | SHEET NO.: | 1/1 |
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




// Core workbook builder
function buildMasterfileWorkbook(parsedTable, specs, sourceText) {
  const insertedPartsSet = new Set();
  const partsKeywords = ["Tube Bundle","Shell","Channel","Head","Bottom Head","Top Head"];

  const aoa = [];

  // --- header rows (same as before) ---
  const headerRow1 = ["NO.", "EQUIPMENT NO.", "PMT NO.", "EQUIPMENT DESCRIPTION", "PARTS", "PHASE", "FLUID", "MATERIAL ", "", "", "INSULATION", "DESIGN", "", "OPERATING", ""];
  const headerRow2 = ["", "", "", "", "", "", "", "TYPE", "SPEC.", "GRADE", "(yes/No)", "TEMP. (°C)", "PRESSURE (Mpa)", "TEMP. (°C)", "PRESSURE (Mpa)"];
  const headerRow3 = Array(15).fill("");
  aoa.push(headerRow1, headerRow2, headerRow3);

  const dataRows = [];

  // Scan sourceText for unique keywords
  for (let keyword of partsKeywords) {
     if (!insertedPartsSet.has(keyword.toLowerCase())) {
      insertedPartsSet.add(keyword.toLowerCase());

      const row = [
        dataRows.length + 1, // NO.
        "", "", "",          // EQUIPMENT NO., PMT NO., DESCRIPTION
        keyword,             // PARTS (insert keyword here)
        "", "", "", "", "", "", // other columns
        specs.designTemp,
        specs.designPress,
        specs.operatingTemp,
        specs.operatingPress
      ];
      dataRows.push(row);
    }
  }

  // If you also want to parse table rows (like your nozzle table), append them after keyword rows
  // if (parsedTable && parsedTable.length > 1) {
  //   const tableRows = parsedTable.slice(1); // skip table header
  //   for (let r of tableRows) {
  //     while (r.length < 7) r.push("");
  //     const func = r[6] || "";
  //     const parts = r[0] || "NOZZLE";
  //     const row = [
  //       dataRows.length + 1,
  //       "",
  //       "",
  //       func || "NOZZLE",
  //       parts,
  //       "",
  //       "",
  //       r[1] || r[5],  // TYPE
  //       r[4],           // SPEC
  //       r[2],           // GRADE
  //       "",
  //       specs.designTemp,
  //       specs.designPress,
  //       specs.operatingTemp,
  //       specs.operatingPress
  //     ];
  //     dataRows.push(row);
  //   }
  // }

  // Append all data rows to aoa
  for (let r of dataRows) aoa.push(r);

  // --- rest of your sheet building logic remains unchanged ---
  const ws = XLSX.utils.aoa_to_sheet(aoa);
  // apply merges, column widths, styles as before
  const wb = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(wb, ws, "Masterfile");
  return wb;
}


// Download handler
document.getElementById("btnDownload").addEventListener("click", function() {
  const text = document.getElementById("sourceText").value;
  const block = findLargestPipeTable(text);
  let parsed = null;
  if (block) parsed = parseMarkdownTableBlock(block);

  const specs = extractSpecifications(text);   // ← ADD THIS

  const wb = buildMasterfileWorkbook(parsed, specs);   // ← PASS SPECS


  // Use writeFile to prompt download
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
