<?php

if (!function_exists('generatePurRows')) {
    function generatePurRows(array $purItems): string {
        $output = '';

        if (!empty($purItems)) {
            $total_amt = 0;
            $sl        = 1;

            foreach ($purItems as $index => $item) {
                $total_amt += $item['total'];

                $output .= '
                    <tr>
                        <td>' . $sl++ . '</td>
                        <td>' . htmlspecialchars($item['pname']) . '</td>
                        <td>' . htmlspecialchars($item['batch_no']) . '</td>
                        <td>' . (!empty($item['manufacturer_date']) ? htmlspecialchars($item['manufacturer_date']) : '-') . '</td>
                        <td>' . (!empty($item['expire_date']) ? htmlspecialchars($item['expire_date']) : '-') . '</td>
                        <td>' . $item['quantity'] . '</td>
                        <td>' . htmlspecialchars($item['unit_name']) . '</td>
                        <td>' . number_format($item['price'], 2) . '</td>
                        <td>' . number_format($item['tax_rate'], 2) . '%</td>
                        <td>' . number_format($item['total'], 2) . '</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info edit_row"
                                data-pid="' . $item['product_id'] . '"
                                data-quantity="' . $item['quantity'] . '"
                                data-price="' . $item['price'] . '"
                                data-taxrate="' . $item['tax_rate'] . '"
                                data-total="' . $item['total'] . '"
                                data-unitname="' . htmlspecialchars($item['unit_name']) . '"
                                data-pname="' . htmlspecialchars($item['pname']) . '"
                                data-mfg="' . ($item['manufacturer_date'] ?? '') . '"
                                data-exp="' . ($item['expire_date'] ?? '') . '"
                            >Edit</button>

                            <button type="button" class="btn btn-sm btn-danger delete_row"
                                data-pid="' . $item['product_id'] . '"
                            >Delete</button>
                        </td>
                    </tr>';
            }

            $output .= '<tr>
                            <td style="text-align: right;" colspan="9"><b>Total:</b></td>
                            <td><b>' . number_format($total_amt, 2) . '</b></td>
                            <td></td>
                        </tr>';
        } else {
            $output .= '<tr><td colspan="11" class="text-center">No items added.</td></tr>';
        }

        return $output;
    }

}

use App\AppSettings;

function AppSetting($key) {
    return AppSettings::pluck($key)[0];
}
