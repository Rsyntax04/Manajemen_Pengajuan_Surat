import os, zipfile, re
path = r"storage/app/public/template/1783473451_Template_Surat_Tugas_Pengembangan_Diri.docx"
with zipfile.ZipFile(path) as z:
    for name in z.namelist():
        if not name.endswith('.xml'):
            continue
        data = z.read(name).decode('utf-8', errors='ignore')
        matches = re.findall(r'\{\{[^{}]+\}\}|\[\[[^\[\]]+\]\]|\$\{[^{}]+\}|\$[A-Za-z0-9_]+', data)
        if matches:
            print(name)
            for m in sorted(set(matches)):
                print(' ', m)
