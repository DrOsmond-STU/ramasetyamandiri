# Rama Setya Mandiri — Website Baru (Rebranding)

Desain ulang website **ramasetyamandiri.com** dengan tema perjalanan udara (langit, awan bergerak, siluet pesawat) yang elegan dan modern. Dibuat sebagai situs statis (HTML/CSS/JS murni) sehingga ringan, cepat, dan mudah di-host di mana saja — termasuk di subdomain staging `new.ramasetyamandiri.com` yang sudah tersedia di hosting Anda.

## Struktur file

```
index.html            Halaman utama (satu halaman, semua section)
assets/css/style.css  Semua styling & animasi
assets/js/main.js     Interaksi: nav sticky, menu mobile, animasi scroll,
                       counter statistik, form pencarian -> WhatsApp
```

Tidak ada dependency eksternal (tanpa framework/build step) selain Google Fonts (Playfair Display + Poppins). Tinggal buka `index.html` di browser, atau upload seluruh folder ke hosting.

## Elemen desain utama

- **Visual langit & awan** — hero dengan gradasi langit (biru gelap → biru muda) dan awan yang melayang perlahan (CSS animation).
- **Pesawat terbang** — siluet pesawat mengikuti jalur lengkung (`offset-path`) melintasi hero, meniru rute penerbangan.
- **Kartu pencarian cepat** — form "Dari / Ke / Tanggal / Penumpang" di bagian atas halaman, langsung membentuk pesan WhatsApp berisi detail pencarian.
- **Animasi interaktif** — hover pada tombol, kartu layanan, dan destinasi memberi efek terangkat/bergerak; elemen section muncul dengan animasi *reveal* saat di-scroll.
- **Palet elegan** — navy & sky blue sebagai warna utama, emas (gold) sebagai aksen premium.

## Konten yang WAJIB diganti sebelum situs ini dipublikasikan

Karena saya tidak bisa mengakses konten asli dari `ramasetyamandiri.com` (domain tersebut diblokir oleh proxy jaringan sandbox, dan WP-CLI di server mengalami error internal akibat konflik plugin AMP/W3TC), seluruh teks berikut adalah **contoh/placeholder** yang perlu diperbarui dengan data asli perusahaan:

| Lokasi | Placeholder saat ini | Ganti dengan |
|---|---|---|
| `assets/js/main.js` → `WHATSAPP_NUMBER` | `6281200000000` | Nomor WhatsApp resmi perusahaan |
| `index.html` → bagian `<footer id="kontak">` | Alamat, telepon, email contoh | Alamat, telepon, dan email resmi |
| `index.html` → section `.testimonials` | 3 testimoni contoh | Testimoni asli dari pelanggan |
| `index.html` → section `.stats-strip` (`data-count`) | 15 / 120 / 25000 / 24 | Angka pencapaian nyata perusahaan |
| `index.html` → link sosial media (`.socials a`) | `href="#"` | Tautan Instagram/Facebook/WhatsApp resmi |
| Daftar layanan & destinasi | Tiket pesawat, tour, umroh, dll. | Sesuaikan bila ada layanan yang berbeda dari bisnis Anda |

Semua lokasi placeholder juga ditandai dengan komentar `<!-- TODO -->` di dalam `index.html`.

## Cara deploy

**Opsi A — Upload manual ke hosting (cPanel):**
1. Upload seluruh isi folder ini ke docroot subdomain, misalnya `new.ramasetyamandiri.com`.
2. Buka `https://new.ramasetyamandiri.com` untuk pratinjau sebelum dipindahkan ke domain utama.

**Opsi B — Ganti situs WordPress lama:**
Situs lama menggunakan WordPress (tema Themify Ultra). Karena situs baru ini statis, migrasi ke domain utama berarti menonaktifkan WordPress di `public_html` dan menggantinya dengan file-file ini (atau redirect/reverse proxy, tergantung preferensi Anda).

> Saya belum meng-upload apa pun ke hosting/domain live — perubahan ini baru ada di branch git `claude/website-rebranding-d8ap0l`. Beri tahu saya jika Anda ingin saya langsung deploy ke `new.ramasetyamandiri.com` untuk pratinjau.

## Kustomisasi cepat

- **Warna**: ubah variabel di bagian `:root` pada `assets/css/style.css` (`--navy`, `--sky-deep`, `--gold`, dst).
- **Font**: ganti tautan Google Fonts di `<head>` pada `index.html` bila ingin tipografi lain.
- **Foto destinasi asli**: kartu destinasi (`.dest-card`) saat ini memakai gradasi warna sebagai placeholder (karena tidak ada foto asli yang bisa saya ambil). Bisa diganti dengan foto asli melalui `background-image` di CSS atau elemen `<img>`.
