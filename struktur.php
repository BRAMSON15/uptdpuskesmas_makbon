<?php
require_once __DIR__ . '/config/database.php';
$page_title = "Struktur Organisasi";
$use_card_wrapper = false;
$strukturList = $pdo->query("SELECT * FROM struktur_organisasi ORDER BY urutan ASC, id ASC")->fetchAll();
function buildFrontTree($elements, $parentId = null) {
    $html = '';
    $children = array_filter($elements, function($e) use ($parentId) { return $e['parent_id'] == $parentId; });
    if (count($children) > 0) {
        $html .= '<ul>';
        foreach ($children as $child) {
            $html .= '<li>';
            $html .= '<div class="node-box">';
            $html .= '<span class="jabatan">' . clean($child['jabatan']) . '</span>';
            $html .= '<span class="nama">' . clean($child['nama']) . '</span>';
            $html .= '</div>';
            $html .= buildFrontTree($elements, $child['id']);
            $html .= '</li>';
        }
        $html .= '</ul>';
    }
    return $html;
}

require_once __DIR__ . "/includes/header.php";
?>
<!--====== STRUKTUR ORGANISASI START ======-->
    <section id="struktur-organisasi" class="pt-130 pb-30" style="background:#f7faf9;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="section_title text-center pb-25">
                        <h3 class="title">Struktur Organisasi</h3>
                        <p>Bagan struktur organisasi di <?= clean($nama_puskesmas) ?></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="org-chart-container wow fadeInUpBig" data-wow-duration="1.3s" data-wow-delay="0.2s">
                        <div class="org-chart">
                            <?php
require_once __DIR__ . '/config/database.php';
$treeHtml = buildFrontTree($strukturList);
                                if ($treeHtml) {
                                    echo $treeHtml;
                                } else {
                                    echo '<p class="text-center">Belum ada data struktur organisasi.</p>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--====== STRUKTUR ORGANISASI ENDS ======-->
<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . "/includes/footer.php"; ?>

