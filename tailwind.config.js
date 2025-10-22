const colors = require('tailwindcss/colors')

module.exports = {
  content: [
      './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
      './storage/framework/views/*.php',
      './resources/views/**/*.blade.php',
      './resources/js/**/*.vue', // <-- Sangat penting untuk Vue
      './resources/js/**/*.js',
      './resources/js/**/*.ts', // <-- Jika Anda menggunakan TypeScript
  ],
  // Pastikan dark mode diaktifkan
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // Warna Primer (Merah Muda/Rose)
        primary: {
          light: colors.rose[400],
          DEFAULT: colors.rose[500], // #F43F5E
          dark: colors.rose[600],
        },
        // Warna Dasar (Latar & Teks) - Menggunakan Slate
        background: {
          light: colors.white,         // Latar terang
          dark: colors.slate[900],     // Latar gelap
        },
        card: {
          light: colors.slate[50],      // Latar kartu terang
          dark: colors.slate[800],      // Latar kartu gelap
        },
        foreground: { // Nama semantik untuk warna teks
          light: colors.slate[900],     // Teks utama terang
          dark: colors.slate[100],      // Teks utama gelap
        },
        muted: { // Nama semantik untuk teks sekunder/abu-abu
          light: colors.slate[500],     // Teks sekunder terang
          dark: colors.slate[400],      // Teks sekunder gelap
        },
        border: {
          light: colors.slate[200],     // Border terang
          dark: colors.slate[700],      // Border gelap
        },
        // Warna untuk tombol netral/sekunder
        neutral: {
            light: colors.slate[100],    // Latar tombol netral terang
            light_hover: colors.slate[200],
            dark: colors.slate[700],     // Latar tombol netral gelap
            dark_hover: colors.slate[600],
        },
        // Teks putih khusus untuk di atas warna primer
        'primary-foreground': colors.white,
      },
      // Anda juga bisa menyesuaikan font agar mirip Lucide (misal: Inter)
      // fontFamily: {
      //   sans: ['Inter', 'sans-serif'],
      // },
    },
  },
  plugins: [
      // Plugin lain yang mungkin Anda gunakan
  ],
}