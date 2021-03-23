<?php if (!defined('BASE_DIR')) exit('No direct script access allowed');

$urlAjaxSave = SITE_URL . 'admin/options/save';
$urlAjaxDelete = SITE_URL . 'admin/options/delete';

?>
<script>
    function vInit(id, key, val) {
        return {
            id: id,
            key: key,
            val: val,
            message: '',
            show: true,

            send: function() {
                let form = new FormData();
                form.append('_method', 'AJAX');
                form.append('id', this.id);
                form.append('key', this.key);
                form.append('val', this.val);
                fetch('{{ $urlAjaxSave }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: form,
                    })
                    .then(r => r.text())
                    .then(r => this.message = r);
            },

            saveBtn: {
                ['@click']() {
                    this.send();
                }
            },

            saveKbd: {
                ['@keydown.enter']() {
                    this.send();
                }
            },

            deleteBtn: {
                ['@click']() {
                    if (confirm('Delete this option? (' + this.key + ')')) {
                        let form = new FormData();
                        form.append('_method', 'AJAX');
                        form.append('id', this.id);
                        fetch('{{ $urlAjaxDelete }}', {
                                method: 'POST',
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: form,
                            })
                            .then(r => r.text())
                            .then(r => {
                                this.message = r;
                                if (r == 'Ok!') this.show = false;
                            });
                    }
                }
            },
        }
    }
</script>