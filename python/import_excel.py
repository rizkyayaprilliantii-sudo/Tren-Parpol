import os
import pandas as pd
from sqlalchemy import create_engine, text


# ==========================================
# KONFIGURASI DATABASE
# ==========================================
DB_URI = "mysql+pymysql://root:@127.0.0.1:3306/trenparpol"
engine = create_engine(DB_URI)

file_excel = "Data PKL (Parpol).xlsx"


# ==========================================
# CEK FILE EXCEL
# ==========================================
if not os.path.exists(file_excel):
    print(f"ERROR: File '{file_excel}' tidak ditemukan.")
    exit()

print(f"Memulai import dari '{file_excel}'...")
print()


# ==========================================
# FUNGSI BACA EXCEL
# ==========================================
def read_sheet(sheet_name):
    df = pd.read_excel(
        file_excel,
        sheet_name=sheet_name
    )

    df.columns = df.columns.astype(str).str.strip()

    df.dropna(
        how="all",
        inplace=True
    )

    df.dropna(
        axis=1,
        how="all",
        inplace=True
    )

    return df


# ==========================================
# FUNGSI CLEAN NUMERIC
# ==========================================
def clean_numeric(value):
    if pd.isna(value):
        return 0

    value = str(value)

    cleaned = "".join(
        char
        for char in value
        if char.isdigit() or char == "-"
    )

    if cleaned == "" or cleaned == "-":
        return 0

    return int(cleaned)


# ==========================================
# CEK SHEET EXCEL
# ==========================================
required_sheets = [
    "Kecamatan",
    "Kelurahan",
    "TPS",
    "Partai",
    "DPT 2019",
    "DPT 2024",
    "Suara Partai 2019",
    "Suara Partai 2024",
]

excel_file = pd.ExcelFile(file_excel)

missing_sheets = [
    sheet
    for sheet in required_sheets
    if sheet not in excel_file.sheet_names
]

if missing_sheets:
    print("ERROR: Sheet berikut tidak ditemukan:")

    for sheet in missing_sheets:
        print(f"  - {sheet}")

    print()
    print("Import dibatalkan.")
    exit()


print("Semua sheet Excel ditemukan.")
print()


# ==========================================
# BACA SEMUA DATA EXCEL
# ==========================================
df_kecamatan = read_sheet("Kecamatan")
df_kelurahan = read_sheet("Kelurahan")
df_tps = read_sheet("TPS")
df_partai = read_sheet("Partai")
df_dpt_2019 = read_sheet("DPT 2019")
df_dpt_2024 = read_sheet("DPT 2024")
df_suara_2019 = read_sheet("Suara Partai 2019")
df_suara_2024 = read_sheet("Suara Partai 2024")


# ==========================================
# TAMPILKAN JUMLAH DATA
# ==========================================
print("Jumlah data dari Excel:")
print(f"Kecamatan          : {len(df_kecamatan)}")
print(f"Kelurahan          : {len(df_kelurahan)}")
print(f"TPS                : {len(df_tps)}")
print(f"Partai             : {len(df_partai)}")
print(f"DPT 2019           : {len(df_dpt_2019)}")
print(f"DPT 2024           : {len(df_dpt_2024)}")
print(f"Suara Partai 2019  : {len(df_suara_2019)}")
print(f"Suara Partai 2024  : {len(df_suara_2024)}")
print()


# ==========================================
# VALIDASI JUMLAH DATA DASAR
# ==========================================
if len(df_kecamatan) != 5:
    print("ERROR: Jumlah kecamatan bukan 5.")
    exit()

if len(df_kelurahan) != 52:
    print("ERROR: Jumlah kelurahan bukan 52.")
    exit()

if len(df_tps) != 1940:
    print("ERROR: Jumlah TPS bukan 1940.")
    exit()

if len(df_partai) != 20:
    print("ERROR: Jumlah partai bukan 20.")
    exit()

if len(df_dpt_2019) != 5:
    print("ERROR: Jumlah DPT 2019 bukan 5.")
    exit()

if len(df_dpt_2024) != 1940:
    print("ERROR: Jumlah DPT 2024 bukan 1940.")
    exit()

if len(df_suara_2019) != 80:
    print("ERROR: Jumlah suara partai 2019 bukan 80.")
    exit()

if len(df_suara_2024) != 34920:
    print("ERROR: Jumlah suara partai 2024 bukan 34920.")
    exit()


# ==========================================
# PROSES KECAMATAN
# ==========================================
df_kecamatan["No"] = df_kecamatan["No"].apply(clean_numeric)

df_kecamatan = df_kecamatan[
    ["No", "Nama Kecamatan"]
].copy()

df_kecamatan.columns = [
    "id",
    "nama_kecamatan"
]


# ==========================================
# PROSES PARTAI
# ==========================================
df_partai["No"] = df_partai["No"].apply(clean_numeric)

df_partai = df_partai[
    [
        "No",
        "Singkatan Parpol",
        "Nama Partai Politik"
    ]
].copy()

df_partai.columns = [
    "id",
    "singkatan_parpol",
    "nama_lengkap_parpol"
]


# ==========================================
# MAPPING KECAMATAN
# ==========================================
kecamatan_map = dict(
    zip(
        df_kecamatan["nama_kecamatan"],
        df_kecamatan["id"]
    )
)


# ==========================================
# MAPPING PARTAI
# ==========================================
partai_map = dict(
    zip(
        df_partai["nama_lengkap_parpol"],
        df_partai["id"]
    )
)


# ==========================================
# VALIDASI KECAMATAN
# ==========================================
if df_kecamatan["nama_kecamatan"].duplicated().any():
    print("ERROR: Ada nama kecamatan yang duplikat.")
    exit()


# ==========================================
# VALIDASI PARTAI
# ==========================================
if df_partai["nama_lengkap_parpol"].duplicated().any():
    print("ERROR: Ada nama partai yang duplikat.")
    exit()


# ==========================================
# PROSES KELURAHAN
# ==========================================
df_kelurahan["No"] = df_kelurahan["No"].apply(clean_numeric)

df_kelurahan["id_kecamatan"] = df_kelurahan["Kecamatan"].map(
    kecamatan_map
)

if df_kelurahan["id_kecamatan"].isna().any():

    print("ERROR: Ada kecamatan pada data kelurahan yang tidak ditemukan.")
    print()

    for kecamatan in df_kelurahan.loc[
        df_kelurahan["id_kecamatan"].isna(),
        "Kecamatan"
    ].unique():

        print(f"Kecamatan: {kecamatan}")

    print()
    print("Import dibatalkan.")
    exit()


df_kelurahan = df_kelurahan[
    [
        "No",
        "id_kecamatan",
        "Kelurahan"
    ]
].copy()

df_kelurahan.columns = [
    "id",
    "id_kecamatan",
    "nama_kelurahan"
]


# ==========================================
# MAPPING KELURAHAN
# ==========================================
kelurahan_map = {}

for _, row in df_kelurahan.iterrows():

    key = (
        row["id_kecamatan"],
        row["nama_kelurahan"]
    )

    kelurahan_map[key] = row["id"]


# ==========================================
# PROSES TPS
# ==========================================
df_tps["No"] = df_tps["No"].apply(clean_numeric)

df_tps["id_kecamatan"] = df_tps["Kecamatan"].map(
    kecamatan_map
)

if df_tps["id_kecamatan"].isna().any():

    print("ERROR: Ada kecamatan pada data TPS yang tidak ditemukan.")
    print()

    for kecamatan in df_tps.loc[
        df_tps["id_kecamatan"].isna(),
        "Kecamatan"
    ].unique():

        print(f"Kecamatan: {kecamatan}")

    print()
    print("Import dibatalkan.")
    exit()


# ==========================================
# CARI ID KELURAHAN UNTUK SETIAP TPS
# ==========================================
df_tps["id_kelurahan"] = [
    kelurahan_map.get(
        (
            kecamatan_map.get(kecamatan),
            kelurahan
        )
    )
    for kecamatan, kelurahan in zip(
        df_tps["Kecamatan"],
        df_tps["Kelurahan"]
    )
]


# ==========================================
# DEBUG KELURAHAN TPS
# ==========================================
if df_tps["id_kelurahan"].isna().any():

    print("ERROR: Ada kelurahan pada data TPS yang tidak ditemukan.")
    print()

    print("Daftar kelurahan yang gagal dicocokkan:")

    for kecamatan, kelurahan, id_kelurahan in zip(
        df_tps["Kecamatan"],
        df_tps["Kelurahan"],
        df_tps["id_kelurahan"]
    ):

        if pd.isna(id_kelurahan):

            print(
                f"Kecamatan: {repr(kecamatan)} | "
                f"Kelurahan: {repr(kelurahan)}"
            )

    print()
    print("Import dibatalkan.")
    exit()


df_tps = df_tps[
    [
        "No",
        "id_kecamatan",
        "id_kelurahan",
        "TPS"
    ]
].copy()

df_tps.columns = [
    "id",
    "id_kecamatan",
    "id_kelurahan",
    "nomor_tps"
]


# ==========================================
# MAPPING TPS
# ==========================================
tps_map = {}

for _, row in df_tps.iterrows():

    key = (
        row["id_kecamatan"],
        row["id_kelurahan"],
        row["nomor_tps"]
    )

    tps_map[key] = row["id"]


# ==========================================
# PROSES DPT 2019
# ==========================================
df_dpt_2019["id_kecamatan"] = df_dpt_2019[
    "Kecamatan"
].map(kecamatan_map)

if df_dpt_2019["id_kecamatan"].isna().any():

    print("ERROR: Ada kecamatan pada DPT 2019 yang tidak ditemukan.")
    exit()


df_dpt_2019["DPT Perempuan"] = df_dpt_2019[
    "DPT Perempuan"
].apply(clean_numeric)

df_dpt_2019["DPT Laki Laki"] = df_dpt_2019[
    "DPT Laki Laki"
].apply(clean_numeric)

df_dpt_2019["DPT Total"] = df_dpt_2019[
    "DPT Total"
].apply(clean_numeric)


df_dpt_2019 = df_dpt_2019[
    [
        "id_kecamatan",
        "DPT Perempuan",
        "DPT Laki Laki",
        "DPT Total"
    ]
].copy()

df_dpt_2019.columns = [
    "id_kecamatan",
    "dpt_p",
    "dpt_l",
    "dpt_total"
]


# ==========================================
# PROSES DPT 2024
# ==========================================
df_dpt_2024["id_kecamatan"] = df_dpt_2024[
    "Kecamatan"
].map(kecamatan_map)

if df_dpt_2024["id_kecamatan"].isna().any():

    print("ERROR: Ada kecamatan pada DPT 2024 yang tidak ditemukan.")
    exit()


df_dpt_2024["id_kelurahan"] = [
    kelurahan_map.get(
        (
            kecamatan_map.get(kecamatan),
            kelurahan
        )
    )
    for kecamatan, kelurahan in zip(
        df_dpt_2024["Kecamatan"],
        df_dpt_2024["Kelurahan"]
    )
]


if df_dpt_2024["id_kelurahan"].isna().any():

    print("ERROR: Ada kelurahan pada DPT 2024 yang tidak ditemukan.")
    exit()


df_dpt_2024["id_tps"] = [
    tps_map.get(
        (
            kecamatan_map.get(kecamatan),
            kelurahan_map.get(
                (
                    kecamatan_map.get(kecamatan),
                    kelurahan
                )
            ),
            tps
        )
    )
    for kecamatan, kelurahan, tps in zip(
        df_dpt_2024["Kecamatan"],
        df_dpt_2024["Kelurahan"],
        df_dpt_2024["TPS"]
    )
]


if df_dpt_2024["id_tps"].isna().any():

    print("ERROR: Ada TPS pada DPT 2024 yang tidak ditemukan.")
    exit()


for col in [
    "DPT Perempuan",
    "DPT Laki Laki",
    "DPT Total"
]:

    df_dpt_2024[col] = df_dpt_2024[col].apply(
        clean_numeric
    )


df_dpt_2024 = df_dpt_2024[
    [
        "id_kecamatan",
        "id_kelurahan",
        "id_tps",
        "DPT Perempuan",
        "DPT Laki Laki",
        "DPT Total"
    ]
].copy()

df_dpt_2024.columns = [
    "id_kecamatan",
    "id_kelurahan",
    "id_tps",
    "dpt_p",
    "dpt_l",
    "dpt_total"
]


# ==========================================
# PROSES SUARA PARTAI 2019
# ==========================================
df_suara_2019["id_kecamatan"] = df_suara_2019[
    "Kecamatan"
].map(kecamatan_map)

if df_suara_2019["id_kecamatan"].isna().any():

    print("ERROR: Ada kecamatan pada suara 2019 yang tidak ditemukan.")
    exit()


df_suara_2019["id_partai"] = df_suara_2019[
    "Partai"
].map(partai_map)

if df_suara_2019["id_partai"].isna().any():

    print("ERROR: Ada partai pada suara 2019 yang tidak ditemukan.")
    exit()


df_suara_2019["Jumlah Suara"] = df_suara_2019[
    "Jumlah Suara"
].apply(clean_numeric)


df_suara_2019 = df_suara_2019[
    [
        "id_kecamatan",
        "id_partai",
        "Jumlah Suara"
    ]
].copy()

df_suara_2019.columns = [
    "id_kecamatan",
    "id_partai",
    "jumlah_suara"
]


# ==========================================
# PROSES SUARA PARTAI 2024
# ==========================================
df_suara_2024["id_kecamatan"] = df_suara_2024[
    "Kecamatan"
].map(kecamatan_map)

if df_suara_2024["id_kecamatan"].isna().any():

    print("ERROR: Ada kecamatan pada suara 2024 yang tidak ditemukan.")
    exit()


df_suara_2024["id_kelurahan"] = [
    kelurahan_map.get(
        (
            kecamatan_map.get(kecamatan),
            kelurahan
        )
    )
    for kecamatan, kelurahan in zip(
        df_suara_2024["Kecamatan"],
        df_suara_2024["Kelurahan"]
    )
]


if df_suara_2024["id_kelurahan"].isna().any():

    print("ERROR: Ada kelurahan pada suara 2024 yang tidak ditemukan.")
    exit()


df_suara_2024["id_tps"] = [
    tps_map.get(
        (
            kecamatan_map.get(kecamatan),
            kelurahan_map.get(
                (
                    kecamatan_map.get(kecamatan),
                    kelurahan
                )
            ),
            tps
        )
    )
    for kecamatan, kelurahan, tps in zip(
        df_suara_2024["Kecamatan"],
        df_suara_2024["Kelurahan"],
        df_suara_2024["TPS"]
    )
]


if df_suara_2024["id_tps"].isna().any():

    print("ERROR: Ada TPS pada suara 2024 yang tidak ditemukan.")
    exit()


df_suara_2024["id_partai"] = df_suara_2024[
    "Partai"
].map(partai_map)

if df_suara_2024["id_partai"].isna().any():

    print("ERROR: Ada partai pada suara 2024 yang tidak ditemukan.")
    exit()


df_suara_2024["Jumlah Suara"] = df_suara_2024[
    "Jumlah Suara"
].apply(clean_numeric)


df_suara_2024 = df_suara_2024[
    [
        "id_kecamatan",
        "id_kelurahan",
        "id_tps",
        "id_partai",
        "Jumlah Suara"
    ]
].copy()

df_suara_2024.columns = [
    "id_kecamatan",
    "id_kelurahan",
    "id_tps",
    "id_partai",
    "jumlah_suara"
]


# ==========================================
# VALIDASI FINAL
# ==========================================
print("Validasi mapping selesai.")
print()

print("Jumlah data siap di-import:")
print(f"master_kecamatan          : {len(df_kecamatan)}")
print(f"master_kelurahan          : {len(df_kelurahan)}")
print(f"master_tps                : {len(df_tps)}")
print(f"master_partai             : {len(df_partai)}")
print(f"master_dpt_2019           : {len(df_dpt_2019)}")
print(f"master_dpt_2024           : {len(df_dpt_2024)}")
print(f"master_suara_partai_2019  : {len(df_suara_2019)}")
print(f"master_suara_partai_2024  : {len(df_suara_2024)}")
print()


# ==========================================
# TABEL YANG AKAN DI-REFRESH
# ==========================================
tables = [
    "master_suara_partai_2024",
    "master_suara_partai_2019",
    "master_dpt_2024",
    "master_dpt_2019",
    "master_tps",
    "master_kelurahan",
    "master_partai",
    "master_kecamatan",
]


# ==========================================
# KOSONGKAN TABEL MASTER
# ==========================================
print("Mengosongkan tabel master lama...")

with engine.begin() as conn:

    conn.execute(
        text("SET FOREIGN_KEY_CHECKS = 0")
    )

    for table in tables:

        conn.execute(
            text(f"TRUNCATE TABLE `{table}`")
        )

    conn.execute(
        text("SET FOREIGN_KEY_CHECKS = 1")
    )

print("Tabel master lama berhasil dikosongkan.")
print()


# ==========================================
# IMPORT MASTER KECAMATAN
# ==========================================
df_kecamatan.to_sql(
    "master_kecamatan",
    engine,
    if_exists="append",
    index=False,
    chunksize=1000
)

print("master_kecamatan berhasil di-import.")


# ==========================================
# IMPORT MASTER PARTAI
# ==========================================
df_partai.to_sql(
    "master_partai",
    engine,
    if_exists="append",
    index=False,
    chunksize=1000
)

print("master_partai berhasil di-import.")


# ==========================================
# IMPORT MASTER KELURAHAN
# ==========================================
df_kelurahan.to_sql(
    "master_kelurahan",
    engine,
    if_exists="append",
    index=False,
    chunksize=1000
)

print("master_kelurahan berhasil di-import.")


# ==========================================
# IMPORT MASTER TPS
# ==========================================
df_tps.to_sql(
    "master_tps",
    engine,
    if_exists="append",
    index=False,
    chunksize=1000
)

print("master_tps berhasil di-import.")


# ==========================================
# IMPORT DPT 2019
# ==========================================
df_dpt_2019.to_sql(
    "master_dpt_2019",
    engine,
    if_exists="append",
    index=False,
    chunksize=1000
)

print("master_dpt_2019 berhasil di-import.")


# ==========================================
# IMPORT DPT 2024
# ==========================================
df_dpt_2024.to_sql(
    "master_dpt_2024",
    engine,
    if_exists="append",
    index=False,
    chunksize=1000
)

print("master_dpt_2024 berhasil di-import.")


# ==========================================
# IMPORT SUARA PARTAI 2019
# ==========================================
df_suara_2019.to_sql(
    "master_suara_partai_2019",
    engine,
    if_exists="append",
    index=False,
    chunksize=1000
)

print("master_suara_partai_2019 berhasil di-import.")


# ==========================================
# IMPORT SUARA PARTAI 2024
# ==========================================
df_suara_2024.to_sql(
    "master_suara_partai_2024",
    engine,
    if_exists="append",
    index=False,
    chunksize=1000
)

print("master_suara_partai_2024 berhasil di-import.")


# ==========================================
# SELESAI
# ==========================================
print()
print("Import selesai.")
print()
print("Tabel master yang diperbarui:")
print("master_kecamatan")
print("master_kelurahan")
print("master_tps")
print("master_partai")
print("master_dpt_2019")
print("master_dpt_2024")
print("master_suara_partai_2019")
print("master_suara_partai_2024")
print()
print("master_rekap_suara_2024 tidak di-import.")