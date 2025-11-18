# extract_table.py
import camelot
import sys
import json

pdf_path = sys.argv[1]

tables = camelot.read_pdf(pdf_path, pages="all", flavor="lattice")

output = []

for t in tables:
    output.append(t.df.values.tolist())

print(json.dumps(output))
