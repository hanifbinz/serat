<!DOCTYPE html>
<html>
<head>
    <style>
        /* MENGAMBIL FONT MEWAH DARI GOOGLE FONTS */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;1,700&family=Cinzel:wght@700&display=swap');

        @page { margin: 0; padding: 0; }
        body { margin: 0; padding: 0; font-family: 'Arial', sans-serif; }
        
        .bg-img {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1000;
        }

        /* NOMOR SERTIFIKAT (Tetap pakai Arial agar terlihat resmi/formal) */
        .nomor-sertifikat {
            position: absolute;
            top: 170px; 
            width: 100%;
            text-align: center;
            font-family: 'Arial', sans-serif;
            font-size: 18px;
            font-weight: bold;
            color: #333333;
            letter-spacing: 2px;
        }

        /* NAMA PESERTA (Pakai font artistik yang baru) */
        .nama-peserta {
            position: absolute;
            top: 305px; 
            width: 100%;
            text-align: center;
            
            /* INI KODE FONT BARUNYA */
            font-family: 'Playfair Display', serif; 
            
            font-size: 55px; /* Sedikit diperbesar karena font serif biasanya lebih kecil */
            font-weight: 700;
            color: #1a202c;
            text-transform: uppercase; /* Memaksa huruf besar semua */
        }
    </style>
</head>
<body>
    @if($base64Image)
        <img src="{{ $base64Image }}" class="bg-img" />
    @endif
    
    <div class="nomor-sertifikat">
        NOMOR: SCAR/2026/VI/{{ str_pad($participant->id, 3, '0', STR_PAD_LEFT) }}
    </div>

    <div class="nama-peserta">
        {{ $participant->name }}
    </div>
</body>
</html>