<?php
/** @var string $val_start */
/** @var string $val_end */
/** @var array $rows */

$loading      = '<div class="loading" style="display:none;"><div></div><div></div><div></div><div></div></div>';
$courses_list = [];
?>

<div class="wrap report">

    <header class="header-report">

        <form id="frm-search" class="frm-search">
            <section class="date-range">
                <div>
                    <label for="start-date">Desde:</label>
                    <input type="date" id="start-date" value="<?= $val_start ?>"/>
                </div>
                <div>
                    <label for="end-date">Hasta:</label>
                    <input type="date" id="end-date" value="<?= $val_end ?>"/>
                </div>
            </section>

            <section class="courses-search">
                <label for="course-search-hint">Curso:</label>
                <input type="text" id="course-search-hint" placeholder="Buscar por nombre del curso"/>
                <button id="btn-search-courses" class="btn-search button button-primary">Filtrar</button>
				<?php echo $loading ?>
            </section>

            <section class="courses-selection">
                <span>Cursos encontrados:</span>
                <ul id="select-courses">
					<?php
					if ( empty( $courses_list ) ) {
						echo '<li>&nbsp;</li>';
					}
					?>
					<?php foreach ( $courses_list as $key => $course ): ?>
                        <li>
                            <label class="course">
                                <input type="checkbox" name="course[]" id="id_<?= $key ?>"> <?= $course ?>
                            </label>
                        </li>
					<?php endforeach; ?>
                </ul>
            </section>

            <section class="buttons students-search">
                <button id="btn-search-students" class="btn-search button button-primary" disabled>Buscar alumnos
                </button>
                <button id="btn-reset-students" class="btn-reset button button-secondary" disabled>Limpiar</button>
				<?php echo $loading ?>
            </section>
        </form>

        <section class="buttons-export">
            <form method="post" id="frm-export" class="frm-export" action="<?php echo admin_url( 'admin-post.php' ) ?>">
                <input type="hidden" value="<?= $val_start ?>">
                <input type="hidden" value="<?= $val_end ?>">
                <input type="hidden" name="action" value="process_export_pin_sent">
                <button type="submit"
                        class="btn-export button button-primary"
                        disabled><?php _e( 'Exportar', 'dcms-send-pin' ) ?></button>
            </form>
        </section>

    </header>

	<?php
	$fields = [ 'ID', 'Nombre', 'Correo', 'Teléfono' ];
	?>

    <table id="students-courses-table" class="dcms-table">
        <thead>
        <tr>
			<?php
			foreach ( $fields as $field ) {
				echo "<th>" . $field . "</th>";
			}
			?>
        </tr>
        </thead>
        <tbody>
		<?php foreach ( $rows as $row ): ?>
            <tr>
                <td></td>
            </tr>
		<?php endforeach; ?>
        </tbody>
    </table>


</div>