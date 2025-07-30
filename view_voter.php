<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit('Invalid voter ID');
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM voters WHERE id = ?");
$stmt->execute([$id]);
$voter = $stmt->fetch();

if (!$voter) {
    exit('Voter not found');
}

// Organization info (hardcoded)
$orgName = "OGSIMER CARES DIVISION ON SPECIAL CONCERN";
$presidentName = "JULITO D. OGSIMER";
$presidentTitle = "ORGANIZATION PRESIDENT";
$orgLogo = "assets/uploads/bg-logo.png";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Member Card - <?= htmlspecialchars($voter['first_name'] . ' ' . $voter['last_name']) ?></title>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8f8f8;
        padding: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    #card-screen {
        width: 4in;
        height: 2in;
        background: #fff6e5;
        border: 2px solid #e88e22;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        padding: 10px 15px;
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Decorative stripes */
    #card-screen::before, #card-screen::after {
        content: '';
        position: absolute;
        width: 60px;
        height: 60px;
        background: rgba(255, 180, 90, 0.3);
        transform: rotate(45deg);
        z-index: 0;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    #card-screen::before {
        top: -30px;
        left: -30px;
    }

    #card-screen::after {
        bottom: -30px;
        right: -30px;
    }

    .left-side {
        width: 35%;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: center;
        position: relative;
        z-index: 1;
    }

    .org-logo {
        width: 50px;
        height: 50px;
        object-fit: scale-down;
        border: none;
        margin-bottom: 0px;
        background: transparent;
    }

    .profile-pic {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e88e22;
        background: white;
    }

    .left-side .member-card-label {
        margin-top: 5px;
        font-style: italic;
        font-weight: bold;
        font-size: 12px;
        color: #b15500;
    }

    .right-side {
        width: 65%;
        padding-left: 15px;
        position: relative;
        z-index: 1;
    }

    .org-name {
        font-weight: 900;
        font-size: 14px;
        margin-bottom: 15px;
        color: #d86c00;
    }

    .member-name {
        font-weight: 900;
        font-size: 18px;
        margin: 0;
        line-height: 1.1;
        letter-spacing: 0.05em;
        color: #000;
    }

    .member-info-row {
        display: flex;
        gap: 10px;
        font-weight: 700;
        font-size: 13px;
        line-height: 1.0;
        color: #111;
        text-transform: uppercase;
    }

    .member-info {
        margin-top: 5px;
        font-weight: 700;
        font-size: 13px;
        line-height: 1.0;
        color: #111;
        text-transform: uppercase;
    }

    .member-number {
        font-weight: 700;
        font-size: 14px;
        margin-top: 8px;
        color: #000;
    }

    .president {
        position: absolute;
        bottom: 10px;
        right: 0;
        text-align: right;
        font-weight: 700;
        font-size: 11px;
        color: #222;
        line-height: 1.1;
    }
    .president-name {
        font-weight: 900;
        font-size: 13px;
    }

    .no-photo {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: #ccc;
        line-height: 110px;
        color: #666;
        font-size: 14px;
        text-align: center;
    }

    .no-print {
        margin-top: 40px;
        text-align: center;
    }

    .no-print button {
        font-size: 16px;
        padding: 10px 25px;
        cursor: pointer;
        background: #e88e22;
        color: white;
        border: none;
        border-radius: 5px;
        transition: background 0.3s ease;
        margin: 0 5px;
    }

    .no-print button:hover {
        background: #cc7400;
    }

    /* Print styles - preserve everything */
    @media print {
        body {
            background: white !important;
            padding: 0 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            height: 100vh !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .no-print {
            display: none !important;
        }

        #card-screen {
            box-shadow: none !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            background: #fff6e5 !important;
            border: 2px solid #e88e22 !important;
            width: 4in !important;
            height: 2in !important;
            border-radius: 12px !important;
            padding: 10px 15px !important;
            display: flex !important;
            position: relative !important;
            overflow: hidden !important;
            page-break-after: always !important;
        }

        #card-screen::before, #card-screen::after {
            background: rgba(255, 180, 90, 0.3) !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
</head>
<body>
    <div id="card-screen">
        <div class="left-side">
            <img src="<?= htmlspecialchars($orgLogo) ?>" alt="Organization Logo" class="org-logo" />
            <?php if (!empty($voter['profile_pic'])): ?>
                <img src="uploads/<?= htmlspecialchars($voter['profile_pic']) ?>" alt="Member Photo" class="profile-pic" />
            <?php else: ?>
                <div class="no-photo">No Photo</div>
            <?php endif; ?>
            <div class="member-card-label">MEMBER CARD</div>
        </div>

        <div class="right-side">
            <div class="org-name"><?= htmlspecialchars(strtoupper($orgName)) ?></div>
            <h2 class="member-name"><?= htmlspecialchars(strtoupper($voter['first_name'] . ' ' . $voter['last_name'])) ?></h2>
            <div class="member-info-row">
                <div class="member-info"><?= htmlspecialchars(strtoupper($voter['gender'])) ?></div>
                <div class="member-info">| <?= htmlspecialchars($voter['contact_number']) ?></div>
            </div>
            <div class="member-info"><?= htmlspecialchars(strtoupper($voter['address'])) ?></div>
            <div class="member-info"><?= htmlspecialchars(strtoupper($voter['hhlsl'])) ?></div>
            <div class="member-number"><?= htmlspecialchars($voter['precinct_number']) ?></div>

            <div class="president">
                <div class="president-name"><?= htmlspecialchars(strtoupper($presidentName)) ?></div>
                <div><?= htmlspecialchars(strtoupper($presidentTitle)) ?></div>
            </div>
        </div>
    </div>

    <div class="no-print">
        <button onclick="window.print()">🖨️ Print Member Card</button>
        <button id="download-btn">⬇️ Download Card Image</button>
        <button onclick="window.location.href='voters.php'">⬅️ Back to Voters</button>
    </div>

    <!-- Include html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        document.getElementById('download-btn').addEventListener('click', function() {
            const card = document.getElementById('card-screen');
            html2canvas(card).then(canvas => {
                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = 'member-card-<?= htmlspecialchars(strtolower($voter['first_name'] . '-' . $voter['last_name'])) ?>.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);
                }, 'image/png');
            });
        });
    </script>
</body>
</html>
