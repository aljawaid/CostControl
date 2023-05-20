<div class="page-header">
    <h2><?= t('Hourly Rate') ?></h2>
    <ul class="">
        <li class="">
            <?= $this->modal->medium('plus', t('New hourly rate'), 'HourlyRateController', 'create', array('plugin' => 'CostControl', 'user_id' => $user['id'])) ?>
        </li>
    </ul>
</div>
<?php if (!empty($rates)): ?>
    <table class="">
        <tr class="">
            <th class=""><?= t('Hourly Rate') ?></th>
            <th class=""><?= t('Currency') ?></th>
            <th class=""><?= t('Effective Date') ?></th>
            <th class=""><?= t('Action') ?></th>
        </tr>
        <?php foreach ($rates as $rate): ?>
            <tr class="">
                <td class=""><?= n($rate['rate']) ?></td>
                <td class=""><?= $rate['currency'] ?></td>
                <td class=""><?= $this->dt->date($rate['date_effective']) ?></td>
                <td class="">
                    <?= $this->modal->confirm('trash-o', t('Remove'), 'HourlyRateController', 'confirm', array('plugin' => 'CostControl', 'user_id' => $user['id'], 'rate_id' => $rate['id'])) ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
<?php else: ?>
    <p class="alert"><?= t('There is no hourly rate defined.') ?></p>
<?php endif ?>
