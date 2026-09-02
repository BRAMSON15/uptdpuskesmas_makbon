<?php
require_once __DIR__ . '/includes/auth.php';
$page_title = 'Visual Builder Struktur Organisasi';
require_once __DIR__ . '/includes/layout_top.php';
?>

<!-- Include OrgChart CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/orgchart/3.8.0/css/jquery.orgchart.min.css">
<style>
    #chart-container {
        font-family: Arial, sans-serif;
        height: 600px;
        border: 2px dashed #ccc;
        border-radius: 8px;
        overflow: auto;
        text-align: center;
        background-color: #f7faf9;
        position: relative;
    }
    .orgchart {
        background: none;
    }
    .orgchart .node .title {
        background-color: #0d7c66;
        white-space: nowrap;
        height: auto;
        line-height: 1.3;
        padding: 8px 15px;
        text-overflow: clip !important;
        overflow: visible !important;
        width: 100% !important;
        box-sizing: border-box;
    }
    .orgchart .node .content {
        border-color: #0d7c66;
        white-space: nowrap;
        height: auto;
        line-height: 1.3;
        padding: 8px 15px;
        text-overflow: clip !important;
        overflow: visible !important;
        width: 100% !important;
        box-sizing: border-box;
    }
    .orgchart .node {
        width: max-content !important;
        min-width: 130px;
        cursor: pointer;
        transition: transform 0.2s;
        display: inline-block !important;
    }
    .orgchart .node:hover {
        transform: scale(1.05);
        z-index: 20;
    }
    
    #empty-state-btn {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
    }
    .zoom-controls {
        position: absolute;
        bottom: 20px;
        right: 20px;
        z-index: 100;
        display: flex;
        gap: 8px;
        background: rgba(255,255,255,0.9);
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        border: 1px solid #ddd;
    }
    .zoom-controls button {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 1.2rem;
    }
</style>

<div class="panel">
    <div class="panel-head">
        <h2>Bagan Struktur Organisasi (Visual Editor)</h2>
        <p class="text-muted" style="margin-top: 5px;">Klik pada kotak bagan untuk mengedit, menambah bawahan, atau menghapusnya.</p>
    </div>
    
    <div id="chart-container">
        <div class="zoom-controls">
            <button class="btn btn-secondary" id="btnZoomIn" title="Perbesar"><i class="lni lni-plus"></i></button>
            <button class="btn btn-secondary" id="btnZoomOut" title="Perkecil"><i class="lni lni-minus"></i></button>
            <button class="btn btn-secondary" id="btnZoomReset" title="Reset Zoom"><i class="lni lni-reload"></i></button>
        </div>
        <button id="empty-state-btn" class="btn btn-primary btn-lg" onclick="addRootNode()">Buat Struktur Paling Atas (Root)</button>
    </div>
</div>

<!-- Modal Edit Node -->
<div class="modal fade" id="nodeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Kelola Bagan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="nodeForm">
                    <input type="hidden" id="nodeId">
                    <div class="form-group">
                        <label>Jabatan</label>
                        <input type="text" class="form-control" id="nodeJabatan" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Pegawai</label>
                        <input type="text" class="form-control" id="nodeNama" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-danger" id="btnDelete">Hapus</button>
                </div>
                <div>
                    <button type="button" class="btn btn-success" id="btnAddChild">Tambah Bawahan</button>
                    <button type="button" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery (Ensure it's loaded, Admin template usually has it) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Bootstrap JS for Modal (Admin template might already have it, but just in case) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Include OrgChart JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/orgchart/3.8.0/js/jquery.orgchart.min.js"></script>

<script>
    var oc = null;

    function initChart(datascource) {
        if (oc) {
            oc.init({'data': datascource});
        } else {
            oc = $('#chart-container').orgchart({
                'data' : datascource,
                'nodeTitle': 'title',
                'nodeContent': 'name',
                'pan': true,
                'zoom': true,
                'createNode': function($node, data) {
                    $node.on('click', function() {
                        openModal(data.id, data.title, data.name);
                    });
                }
            });
        }
    }

    function loadChart() {
        $.getJSON('struktur_organisasi_api.php?action=get', function(data) {
            if (data) {
                $('#empty-state-btn').hide();
                initChart(data);
            } else {
                $('#chart-container').empty();
                $('#empty-state-btn').appendTo('#chart-container').show();
            }
        });
    }

    function addRootNode() {
        $.post('struktur_organisasi_api.php', {
            action: 'add',
            parent_id: '',
            jabatan: 'Kepala Puskesmas',
            nama: 'Nama Pegawai'
        }, function(res) {
            if (res.status === 'success') {
                loadChart();
            }
        });
    }

    function openModal(id, jabatan, nama) {
        $('#nodeId').val(id);
        $('#nodeJabatan').val(jabatan);
        $('#nodeNama').val(nama);
        $('#nodeModal').modal('show');
    }

    $(document).ready(function() {
        loadChart();

        $('#btnSave').click(function() {
            var id = $('#nodeId').val();
            var jabatan = $('#nodeJabatan').val();
            var nama = $('#nodeNama').val();
            
            $.post('struktur_organisasi_api.php', {
                action: 'edit',
                id: id,
                jabatan: jabatan,
                nama: nama
            }, function(res) {
                if (res.status === 'success') {
                    $('#nodeModal').modal('hide');
                    loadChart();
                }
            });
        });

        $('#btnAddChild').click(function() {
            var parentId = $('#nodeId').val();
            
            $.post('struktur_organisasi_api.php', {
                action: 'add',
                parent_id: parentId,
                jabatan: 'Jabatan Baru',
                nama: 'Nama Pegawai'
            }, function(res) {
                if (res.status === 'success') {
                    $('#nodeModal').modal('hide');
                    loadChart();
                }
            });
        });

        $('#btnDelete').click(function() {
            if(confirm('Apakah Anda yakin ingin menghapus bagian ini beserta seluruh bawahannya?')) {
                var id = $('#nodeId').val();
                $.post('struktur_organisasi_api.php', {
                    action: 'delete',
                    id: id
                }, function(res) {
                    if (res.status === 'success') {
                        $('#nodeModal').modal('hide');
                        loadChart();
                    }
                });
            }
        });

        // Zoom Controls
        function setChartScale(scaleChange, reset = false) {
            var $chart = $('.orgchart');
            if (!$chart.length) return;
            
            var matrix = $chart.css('transform');
            var vals = [1, 0, 0, 1, 0, 0];
            if (matrix !== 'none') {
                vals = matrix.split('(')[1].split(')')[0].split(',').map(parseFloat);
            }
            
            if (reset) {
                vals[0] = 1; 
                vals[3] = 1; 
                vals[4] = 0; 
                vals[5] = 0; 
            } else {
                var newScale = vals[0] + scaleChange;
                if (newScale < 0.2) newScale = 0.2;
                if (newScale > 3) newScale = 3;
                vals[0] = newScale;
                vals[3] = newScale;
            }
            $chart.css('transform', 'matrix(' + vals.join(',') + ')');
        }

        $('#btnZoomIn').click(function() { setChartScale(0.1); });
        $('#btnZoomOut').click(function() { setChartScale(-0.1); });
        $('#btnZoomReset').click(function() { setChartScale(0, true); });

    });
</script>

<?php require_once __DIR__ . '/includes/layout_bottom.php'; ?>
