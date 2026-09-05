<?php
$files = ["admin/includes/layout_top.php", "petugas/includes/layout_top.php"];

$css_revamp = <<<EOD
        /* ==== REVAMP STYLES ==== */
        .card {
            border: none !important;
            border-radius: 14px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04), 0 1px 3px rgba(0,0,0,0.02) !important;
            transition: transform 0.3s ease, box-shadow 0.3s ease !important;
            margin-bottom: 25px;
            background: #ffffff;
        }
        .card:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.07) !important;
        }
        .card-header {
            background-color: transparent !important;
            border-bottom: 1px solid var(--border-color) !important;
            padding: 20px 25px !important;
            font-weight: 700 !important;
            font-size: 1.1rem !important;
        }
        .btn {
            border-radius: 8px !important;
            font-weight: 600 !important;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        .table th {
            font-weight: 700;
            color: var(--text-muted);
            border-top: none !important;
            border-bottom: 2px solid var(--border-color) !important;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .table td {
            vertical-align: middle;
            color: var(--text-main);
            border-bottom: 1px dashed var(--border-color) !important;
            border-top: none !important;
        }
        /* Make sidebar pop */
        .sidebar {
            background: linear-gradient(180deg, #10342a 0%, #081a15 100%) !important;
            box-shadow: 6px 0 20px rgba(0,0,0,0.08) !important;
        }
        .sidebar a.active {
            background: linear-gradient(90deg, #0d7c66 0%, #11a286 100%) !important;
            box-shadow: 0 5px 15px rgba(13, 124, 102, 0.4) !important;
            border-left: 4px solid #4df5d4;
        }
        body {
            background-color: #f2f6f9 !important;
        }
EOD;

foreach ($files as $file) {
    if(file_exists($file)) {
        $content = file_get_contents($file);
        
        // Remove old revamp if exists to avoid duplicates
        $content = preg_replace("/\/\* ==== REVAMP STYLES ====\ *\/\s*.*?(?=<\/style>)/s", "", $content);
        
        // Inject new revamp styles right before </style>
        $content = str_replace("</style>", "\n" . $css_revamp . "\n    </style>", $content);
        
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}
?>
