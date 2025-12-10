<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Certificat de Réussite - <?php echo htmlspecialchars($userPrenom); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #333; /* Fond sombre pour faire ressortir le papier */
            font-family: 'Playfair Display', serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding-bottom: 80px; /* Espace pour la barre du bas */
        }

        /* La feuille A4 */
        .certificat-container {
            width: 297mm; /* Format A4 Paysage */
            height: 210mm;
            background: white;
            padding: 20px;
            position: relative;
            box-shadow: 0 0 50px rgba(0,0,0,0.5); /* Ombre portée réaliste */
            box-sizing: border-box;
            /* Zoom automatique si l'écran est petit */
            transform: scale(0.9); 
        }

        .border-design {
            border: 5px double #2c3e50;
            height: 100%;
            padding: 40px;
            position: relative;
            box-sizing: border-box;
            text-align: center;
        }

        /* Décorations de coin */
        .corner {
            position: absolute;
            width: 50px;
            height: 50px;
            border-color: #d4af37; /* Or */
            border-style: solid;
        }
        .top-left { top: 20px; left: 20px; border-width: 5px 0 0 5px; }
        .top-right { top: 20px; right: 20px; border-width: 5px 5px 0 0; }
        .bottom-left { bottom: 20px; left: 20px; border-width: 0 0 5px 5px; }
        .bottom-right { bottom: 20px; right: 20px; border-width: 0 5px 5px 0; }

        /* Contenu Typographique */
        h1 {
            font-family: 'Great Vibes', cursive;
            font-size: 6em;
            color: #d4af37;
            margin: 20px 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .subtitle {
            font-size: 1.5em;
            text-transform: uppercase;
            letter-spacing: 5px;
            color: #555;
            margin-bottom: 30px;
        }

        .presente-a {
            font-size: 1.2em;
            font-style: italic;
            color: #777;
            margin-bottom: 10px;
        }

        .student-name {
            font-size: 3.5em;
            font-weight: 700;
            color: #2c3e50;
            border-bottom: 2px solid #d4af37;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 20px;
            min-width: 400px;
        }

        .description {
            font-family: 'Roboto', sans-serif;
            font-size: 1.1em;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .course-title {
            font-size: 2.2em;
            margin: 10px 0 30px 0;
            color: #2c3e50;
            font-weight: bold;
        }

        /* Zone Signatures */
        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
        }

        .sig-block { text-align: center; }

        .sig-line {
            width: 250px;
            border-top: 1px solid #333;
            margin-bottom: 10px;
            height: 50px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .sig-text {
            font-family: 'Roboto', sans-serif;
            font-size: 0.9em;
            font-weight: bold;
            color: #2c3e50;
        }

        .logo-assoc {
            position: absolute;
            bottom: 60px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            opacity: 0.15;
        }

        /* BARRE D'ACTION EN BAS (NOUVEAU) */
        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
            backdrop-filter: blur(5px);
            z-index: 1000;
        }

        .btn-download {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1.2em;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.4);
            transition: transform 0.2s, background 0.2s;
        }

        .btn-download:hover {
            transform: translateY(-3px);
            background-color: #219150;
        }

        .btn-close {
            background-color: transparent;
            color: #555;
            border: 2px solid #ddd;
            padding: 12px 25px;
            font-size: 1em;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
        }

        .btn-close:hover {
            background-color: #eee;
            color: #333;
        }

        /* Styles d'impression */
        @media print {
            body { 
                background: none; 
                display: block; 
                padding: 0;
            }
            .certificat-container { 
                box-shadow: none; 
                margin: 0; 
                width: 100%; 
                height: 100%; 
                transform: none; /* Pas de zoom à l'impression */
            }
            /* Cacher la barre du bas à l'impression */
            .no-print { display: none !important; }
            @page { size: landscape; margin: 0; }
        }
    </style>
</head>
<body>

    <div class="certificat-container">
        <div class="border-design">
            <div class="corner top-left"></div>
            <div class="corner top-right"></div>
            <div class="corner bottom-left"></div>
            <div class="corner bottom-right"></div>

            <img src="../Images/lg.png" class="logo-assoc" alt="Logo">

            <h1>Certificat de Réussite</h1>
            <div class="subtitle">Formation Citoyenne</div>

            <div class="presente-a">Ce certificat est fièrement décerné à</div>
            
            <div class="student-name"><?php echo htmlspecialchars($userPrenom . ' ' . $userNom); ?></div>

            <div class="description">
                Pour avoir complété avec succès le parcours de sensibilisation et validé tous les modules du thème :
            </div>

            <div class="course-title">"<?php echo htmlspecialchars($themeInfo['titre'] ?? 'Cyberharcèlement'); ?>"</div>

            <div style="margin-top: 20px; font-style: italic; color: #888;">
                Délivré le <?php echo $date; ?>
            </div>

           
        </div>
    </div>

    <div class="bottom-bar no-print">
        <button onclick="window.close()" class="btn-close">
            Fermer
        </button>
        <button onclick="window.print()" class="btn-download">
            <i class="fas fa-file-pdf"></i> Télécharger / Imprimer
        </button>
    </div>

</body>
</html>