import pandas as pd

file_excel = "Master Data.xlsx"

sheet_name = "master_suara_partai_2024"

df = pd.read_excel(
    file_excel,
    sheet_name=sheet_name
)

print("=== SEBELUM CLEANING ===")
print("Jumlah baris:", len(df))

# Bersihkan nama kolom
df.columns = df.columns.str.strip()

# Hapus kolom id seperti yang dilakukan import_excel.py
if "id" in df.columns:
    df = df.drop(columns=["id"])

# Hapus baris/kolom kosong
df.dropna(how="all", inplace=True)
df.dropna(axis=1, how="all", inplace=True)

# Fungsi yang sama dengan import_excel.py
def clean_numeric(value):
    if pd.isna(value):
        return 0

    str_val = str(value)
    cleaned = ''.join(
        c for c in str_val
        if c.isdigit() or c == '-'
    )

    if cleaned == '' or cleaned == '-':
        return 0

    return int(cleaned)

# Convert ID
id_cols = [
    'id_kecamatan',
    'id_kelurahan',
    'id_tps',
    'id_partai'
]

for col in id_cols:
    if col in df.columns:
        df[col] = df[col].apply(clean_numeric)

# Clean kolom numerik
kolom_numeric = [
    'id_kecamatan',
    'id_kelurahan',
    'id_tps',
    'id_partai',
    'jumlah_suara'
]

for col in kolom_numeric:
    if col in df.columns:
        df[col] = df[col].apply(clean_numeric)

print("\n=== SETELAH CLEANING ===")
print("Jumlah baris:", len(df))

print("\n=== TPS 1 + PARTAI 1 ===")
print(
    df[
        (df["id_tps"] == 1) &
        (df["id_partai"] == 1)
    ].to_string(index=False)
)

print("\n=== DUPLIKAT TPS + PARTAI ===")
duplikat = (
    df.groupby(["id_tps", "id_partai"])
      .size()
      .reset_index(name="jumlah")
)

print(
    duplikat[
        duplikat["jumlah"] > 1
    ].to_string(index=False)
)