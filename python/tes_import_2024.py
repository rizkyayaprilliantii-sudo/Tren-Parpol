import pandas as pd
from sqlalchemy import create_engine, text

DB_URI = "mysql+pymysql://root:@127.0.0.1:3306/trenparpol"
engine = create_engine(DB_URI)

file_excel = "Master Data.xlsx"
sheet_name = "master_suara_partai_2024"

# Baca Excel
df = pd.read_excel(
    file_excel,
    sheet_name=sheet_name
)

print("Jumlah data dari Excel:", len(df))

# Hapus kolom id karena AUTO_INCREMENT MySQL
if "id" in df.columns:
    df = df.drop(columns=["id"])

# Bersihkan nama kolom
df.columns = df.columns.str.strip()

# Bersihkan baris kosong
df.dropna(how="all", inplace=True)
df.dropna(axis=1, how="all", inplace=True)

print("Jumlah sebelum import:", len(df))

# Kosongkan tabel
with engine.begin() as conn:
    conn.execute(text("SET FOREIGN_KEY_CHECKS = 0"))
    conn.execute(text("TRUNCATE TABLE master_suara_partai_2024"))
    conn.execute(text("SET FOREIGN_KEY_CHECKS = 1"))

print("Tabel sudah di-TRUNCATE.")

# Import
df.to_sql(
    sheet_name,
    engine,
    if_exists="append",
    index=False,
    chunksize=1000
)

print("Import selesai.")

# Cek jumlah di database
with engine.connect() as conn:
    jumlah = conn.execute(
        text("SELECT COUNT(*) FROM master_suara_partai_2024")
    ).scalar()

print("Jumlah baris di MySQL:", jumlah)