# PT Rama Setya Mandiri — Website Baru (Rebranding)

Desain ulang **ramasetyamandiri.com** dengan tema penerbangan (langit, awan bergerak, siluet pesawat) yang elegan dan profesional — dibangun sebagai situs statis (HTML/CSS/JS murni, tanpa framework/build step).

Konten diambil dari materi resmi perusahaan: *Company Profile PT Rama Setya Mandiri* (file `.pptx` yang diunggah), termasuk logo, foto armada, foto portofolio, dan logo mitra. Teks disusun ulang agar enak dibaca di web, namun seluruh fakta (tanggal berdiri, kapasitas armada, nama mitra, kontak) dipertahankan sesuai company profile.

## Struktur file

```
index.html             Halaman utama (satu halaman, semua section)
assets/css/style.css   Semua styling & animasi
assets/js/main.js      Interaksi: nav sticky, menu mobile, animasi scroll,
                        counter statistik, form pengajuan -> WhatsApp
assets/img/            Logo, foto armada, foto portofolio, logo mitra
                        (diekstrak dari company profile PPTX)
```

Tidak ada dependency eksternal selain Google Fonts (Playfair Display + Poppins). Tinggal buka `index.html` di browser, atau upload seluruh folder ke hosting.

## Tentang perusahaan (ringkasan sumber)

- **PT Rama Setya Mandiri** — Integrated Aviation Solutions: Ground Handling & Exclusive Air Charter.
- Didirikan di Sentani, Papua, 29 September 2022 (akta notaris Dr. H. Tri Mulyadi).
- Resmi & berizin beroperasi sejak 1 Januari 2026, melayani maskapai di beberapa bandara di Papua.
- **Armada**: KAMOV KA32 (kargo, 1–3,5 ton), Airbus Helicopter AS350B3/B2 (penumpang & kargo, 250–600 kg), Grand Caravan Cessna 208B (penumpang & kargo, 700–1.200 kg).
- **Layanan**: Jasa Transportasi Udara, Ground Handling (RAMP, Aviation Security, Bagasi & Kargo, Lavatory, VIP), serta Private & VIP Charter.
- **Kontak resmi**: Jl. Sosial No. 11, Tifa Residence 2, Sentani, Papua 99352 · WA/Telepon 0813-2192-8034 · info@ramasetyamandiri.com

## Elemen desain utama

- **Hero** — foto asli helikopter AS350 di langit Papua, dipadukan lapisan awan CSS yang melayang perlahan dan siluet pesawat mengikuti jalur rute melengkung (`offset-path`).
- **Kartu pengajuan cepat** — form "Dari / Ke / Tanggal / Jenis Muatan" di atas halaman; submit langsung membentuk pesan WhatsApp ke nomor resmi perusahaan.
- **Animasi interaktif** — hover pada kartu layanan, armada, dan portofolio; elemen muncul dengan animasi *scroll-reveal*.
- **Palet**: navy & sky blue sebagai warna utama, emas (gold) sebagai aksen premium. Font Playfair Display (judul) + Poppins (isi).

## Yang perlu dicek sebelum go-live

Karena saya tidak dapat mengakses `ramasetyamandiri.com` secara langsung (domain diblokir oleh proxy jaringan sandbox saya) maupun database WordPress-nya (WP-CLI di server error karena konflik plugin AMP/W3TC), saya tidak bisa membandingkan teks ini dengan copy asli di situs lama. Mohon periksa:

- **Nomor WhatsApp** yang dipakai (`0813-2192-8034`, dari slide kontak) — pastikan ini nomor yang aktif menerima pesan operasional.
- **Angka statistik** di bagian atas (2022 / 3 armada / 10+ mitra / 100% penyelesaian) — diambil dari isi company profile; perbarui jika ada data lebih baru.
- **Testimoni pelanggan** belum ada — bagian ini sengaja tidak dibuat karena saya tidak punya testimoni asli untuk ditampilkan (menghindari kutipan rekaan). Tambahkan jika Anda punya testimoni nyata dari mitra/klien.
- **Foto & logo** di `assets/img/` diekstrak dari file PPTX yang diunggah — pastikan Anda memang memiliki hak penuh atas seluruh foto tersebut sebelum dipublikasikan secara publik.

## Cara deploy

**Opsi A — Upload manual ke hosting (cPanel):**
Upload seluruh isi folder ini ke docroot subdomain `new.ramasetyamandiri.com` (sudah tersedia kosong di hosting Anda) untuk pratinjau sebelum dipindahkan ke domain utama.

**Opsi B — Ganti situs WordPress lama:**
Situs lama menggunakan WordPress (tema Themify Ultra) di `public_html`. Karena situs baru ini statis, migrasi ke domain utama berarti menonaktifkan WordPress dan menggantinya dengan file-file ini.

> Saya belum meng-upload apa pun ke hosting/domain live — perubahan ini baru ada di branch git `claude/website-rebranding-d8ap0l`. Beri tahu saya jika Anda ingin saya langsung deploy ke `new.ramasetyamandiri.com` untuk pratinjau.

## Kustomisasi cepat

- **Warna**: ubah variabel di bagian `:root` pada `assets/css/style.css` (`--navy`, `--sky-deep`, `--gold`, dst).
- **Nomor WhatsApp**: ubah `WHATSAPP_NUMBER` di `assets/js/main.js`.
- **Foto**: ganti file di `assets/img/` dengan nama file yang sama, atau update path-nya di `index.html`.
