<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat {{ $participant->name }}</title>
    <style>
        /* Mengambil Font Allura dari Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Allura&display=swap');

        /* Pengaturan ukuran kertas (A4 Landscape) dan menghilangkan margin */
        @page {
            margin: 0px;
            size: A4 landscape;
        }
        
        body {
            margin: 0px;
            padding: 0px;
            font-family: Arial, Helvetica, sans-serif;
            position: relative;
        }
        
        /* Background Gambar Full Screen */
        .background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; 
        }

        /* Posisi Teks Nama Peserta */
        .text-name {
            position: absolute;
            font-family: 'Allura', cursive; /* Menggunakan font Allura */
            font-size: 60px; /* Sedikit dibesarkan karena Allura karakternya agak ramping */
            font-weight: normal; /* Wajib normal agar font latinnya mulus dan tidak pecah */
            color: #1a1a1a;
            white-space: nowrap; 
        }

        /* Posisi Teks Nomor Seri */
        .text-serial {
            position: absolute;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-weight: bold;
            color: #333333;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    <!-- Gambar Background Sertifikat (Dirender via Base64) -->
    <img src="{{ $base64Image }}" class="background">

    <!-- Teks Nama Peserta -->
    <div class="text-name" style="
        top: {{ $nameY }}px; 
        @if((isset($nameAlign) ? $nameAlign : 'center') == 'center')
            left: 0; width: 100%; text-align: center;
        @else
            left: {{ $nameX }}px;
        @endif
    ">
        {{ $participant->name }}
    </div>

    <!-- Teks Nomor Seri -->
    <div class="text-serial" style="
        top: {{ $serialY }}px; 
        @if((isset($serialAlign) ? $serialAlign : 'center') == 'center')
            left: 0; width: 100%; text-align: center;
        @else
            left: {{ $serialX }}px;
        @endif
    ">
        {{ $serialNumber }}
    </div>

</body>
</html>