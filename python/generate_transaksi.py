import pandas as pd
from sqlalchemy import create_engine, text
import numpy as np

# ==========================================
# KONFIGURASI DATABASE
# ==========================================
DB_URI = "mysql+pymysql://root:@127.0.0.1:3306/trenparpol"
engine = create_engine(DB_URI)

print("=" * 60)
print("🚀 MEMULAI GENERATE DATA TRANSAKSI")
print("=" * 60)

# ==========================================
# 1. GENERATE REKAP SUARA 2019
# ==========================================
print("\n📊 Step 1: Generate Rekap Suara 2019...")

try:
    # Query: Sum semua suara per partai di tahun 2019
    query_2019 = """
        SELECT 
            id_partai,
            SUM(jumlah_suara) as total_suara
        FROM master_suara_partai_2019
        GROUP BY id_partai
        ORDER BY total_suara DESC
    """
    
    df_2019 = pd.read_sql(query_2019, engine)
    
    # Hitung total semua suara (untuk persentase)
    total_semua_suara_2019 = df_2019['total_suara'].sum()
    
    # Hitung persentase
    df_2019['persentase_suara'] = (df_2019['total_suara'] / total_semua_suara_2019 * 100).round(2)
    
    # Hitung peringkat (1 = suara terbanyak)
    df_2019['peringkat'] = range(1, len(df_2019) + 1)
    
    # Kosongkan tabel dulu
    with engine.begin() as conn:
        conn.execute(text("TRUNCATE TABLE rekap_suara_2019"))
    
    # Insert ke database
    df_2019.to_sql('rekap_suara_2019', engine, if_exists='append', index=False)
    
    print(f"✅ SUKSES: {len(df_2019)} partai berhasil direkap")
    print(f"   Total suara 2019: {total_semua_suara_2019:,}")
    
except Exception as e:
    print(f"❌ ERROR di Rekap 2019: {e}")

# ==========================================
# 2. GENERATE REKAP SUARA 2024
# ==========================================
print("\n📊 Step 2: Generate Rekap Suara 2024...")

try:
    # Query: Sum semua suara per partai di tahun 2024 (dari semua TPS)
    query_2024 = """
        SELECT 
            id_partai,
            SUM(jumlah_suara) as total_suara
        FROM master_suara_partai_2024
        GROUP BY id_partai
        ORDER BY total_suara DESC
    """
    
    df_2024 = pd.read_sql(query_2024, engine)
    
    # Hitung total semua suara
    total_semua_suara_2024 = df_2024['total_suara'].sum()
    
    # Hitung persentase
    df_2024['persentase_suara'] = (df_2024['total_suara'] / total_semua_suara_2024 * 100).round(2)
    
    # Hitung peringkat
    df_2024['peringkat'] = range(1, len(df_2024) + 1)
    
    # Kosongkan tabel dulu
    with engine.begin() as conn:
        conn.execute(text("TRUNCATE TABLE rekap_suara_2024"))
    
    # Insert ke database
    df_2024.to_sql('rekap_suara_2024', engine, if_exists='append', index=False)
    
    print(f"✅ SUKSES: {len(df_2024)} partai berhasil direkap")
    print(f"   Total suara 2024: {total_semua_suara_2024:,}")
    
except Exception as e:
    print(f"❌ ERROR di Rekap 2024: {e}")

# ==========================================
# 3. GENERATE HASIL PERBANDINGAN
# ==========================================

print("\n📊 Step 3: Generate Perbandingan 2019 vs 2024...")

try:
    # Gabungkan hanya partai yang tersedia di kedua periode
    df_compare = pd.merge(
        df_2019[['id_partai', 'total_suara']].rename(
            columns={'total_suara': 'suara_2019'}
        ),
        df_2024[['id_partai', 'total_suara']].rename(
            columns={'total_suara': 'suara_2024'}
        ),
        on='id_partai',
        how='inner'
    )

    # Hitung selisih suara
    df_compare['selisih'] = (
        df_compare['suara_2024']
        - df_compare['suara_2019']
    )

    # Hitung persentase perubahan
    df_compare['persentase_perubahan'] = (
        df_compare['selisih']
        / df_compare['suara_2019']
        * 100
    ).round(2)

    # Tentukan status tren
    def tentukan_tren(row):
        if row['selisih'] > 0:
            return 'Naik'
        elif row['selisih'] < 0:
            return 'Turun'
        else:
            return 'Tetap'

    df_compare['status_tren'] = df_compare.apply(
        tentukan_tren,
        axis=1
    )

    # Urutkan berdasarkan selisih terbesar
    df_compare = df_compare.sort_values(
        by='selisih',
        ascending=False
    )

    # Kosongkan tabel dulu
    with engine.begin() as conn:
        conn.execute(text("TRUNCATE TABLE hasil_perbandingan"))

    # Insert ke database
    df_compare.to_sql(
        'hasil_perbandingan',
        engine,
        if_exists='append',
        index=False
    )

    print(
        f"✅ SUKSES: {len(df_compare)} partai berhasil dibandingkan"
    )

    # Tampilkan ringkasan
    naik = len(
        df_compare[df_compare['status_tren'] == 'Naik']
    )

    turun = len(
        df_compare[df_compare['status_tren'] == 'Turun']
    )

    tetap = len(
        df_compare[df_compare['status_tren'] == 'Tetap']
    )

    print(f"   📈 Partai Naik: {naik}")
    print(f"   📉 Partai Turun: {turun}")
    print(f"   ➡️  Partai Tetap: {tetap}")

except Exception as e:
    print(f"❌ ERROR di Perbandingan: {e}")