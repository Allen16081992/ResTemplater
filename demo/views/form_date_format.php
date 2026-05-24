<div class="field">
    <label class="label" for="date">Date of Birth</label>
    <div class="server-field animate__animated animate__shakeX"><?= ViewBook::getError('date') ?></div>
    <div class="control">
        <!-- <input id="date" name="date" type="date" class="input"> Cultprit of rejesting signing in -->
        <div class="select select-group">
            <select name="day" required>
                <option value="" selected disabled>--</option>
            </select>
            <select name="month" required>
                <option value="" selected disabled>--</option>
            </select>
            <select name="year" required>
                <option value="" selected disabled>----</option>
            </select>
        </div>
    </div>
</div>