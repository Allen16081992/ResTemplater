<div class="field">
    <label class="label" for="date">Date of Birth</label>
    <div class="control">
        <input id="date" name="date" type="date" class="input" required>
        <div class="select select-group">
            <select name="day" required>
                <option value="" selected disabled>--</option>
                <?php
                    for ($day = 1; $day <= 31; $day++) {
                        echo '<option value="'.$day.'">'.$day.'</option>';
                    }
                ?>
            </select>
            <select name="month" required>
                <option value="" selected disabled>--</option>
                <?php
                    for ($month = 1; $month <= 12; $month++) {
                        echo '<option value="'.$month.'">'.$month.'</option>';
                    }
                ?>
            </select>
            <select name="year" required>
                <option value="" selected disabled>----</option>
                <?php
                    $currentYear = date('Y');
                    $targetYear = 1908;
                    for ($year = $currentYear - 15; $year >= $targetYear; $year--) {
                        echo '<option value="'.$year.'">'.$year.'</option>';
                    }
                ?>
            </select>
        </div>
    </div>
</div>