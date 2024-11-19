import random

# Veriler
kategoriler = list(range(1, 8))  # Kategori ID'leri
alt_kategoriler = {
    1: [1, 2, 3, 4, 5],
    2: [6, 7, 8, 9, 10, 11, 12, 13],
    3: [14, 15, 16, 17, 18, 19, 20],
    4: [21, 22, 23],
    5: [24, 25, 26, 27, 28, 29],
    6: [30, 31, 32, 33],
    7: [34, 35, 36, 37, 38, 39, 40],
}
alt_kategoriler_alt = {
    1: [41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52],
    3: [53, 54, 55],
    2: [56, 57, 58, 59],
}

isimler = ['Epoksi', 'Polyester', 'Jelkot', 'Cam Elyaf', 'RTV-2 Silikon', 'Kalıp Ayırıcı', 'Pigment', 'Fırça']
aciklamalar = ['Yüksek kaliteli', 'Dayanıklı', 'Kolay uygulanabilir', 'Detaylı işçilik', 'Uygun fiyatlı']
fiyat_araligi = (20, 500)
stok_araligi = (5, 150)

# Rastgele ürün verisi üretimi
print("INSERT INTO `urunler` (`isim`, `aciklama`, `fiyat`, `stok`, `resim`, `kategori_id`, `alt_kategori_id`, `alt_kategori_alt_id`) VALUES")
for i in range(1):
    kategori_id = random.choice(kategoriler)  # Rastgele kategori ID seçimi
    alt_kategori_id = random.choice(alt_kategoriler[kategori_id])  # Kategorinin alt kategorisi seçimi
    
    # Alt kategoriye bağlı alt-alt kategori seçimi
    if alt_kategori_id in alt_kategoriler_alt:
        alt_kategori_alt_id = random.choice(alt_kategoriler_alt[alt_kategori_id])
    else:
        alt_kategori_alt_id = "NULL"  # Alt-alt kategori yoksa NULL atanır

    isim = f"{random.choice(isimler)} {i+1}"  # Ürün ismi
    aciklama = random.choice(aciklamalar)  # Açıklama
    fiyat = round(random.uniform(*fiyat_araligi), 2)  # Fiyat
    stok = random.randint(*stok_araligi)  # Stok
    resim = f"https://example.com/images/urun_{i+1}.jpg"  # Resim URL

    # SQL formatında çıktı
    print(f"('{isim}', '{aciklama}', {fiyat}, {stok}, '{resim}', {kategori_id}, {alt_kategori_id}, {alt_kategori_alt_id}),")

