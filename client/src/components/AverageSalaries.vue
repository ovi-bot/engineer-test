<template>
    <div>
        <table v-if="rows.length" class="table">
            <thead>
            <tr>
                <th class="table__header-cell">Company</th>
                <th class="table__header-cell table__cell--amount">Avg. Salary</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="row in rows" :key="row.id">
                <td class="table__cell">{{ row.name }}</td>
                <td class="table__cell table__cell--amount">
                    {{ currency.format(row.average_salary ?? 0) }}
                </td>
            </tr>
            </tbody>
        </table>

        <div v-else class="empty-message">No companies found.</div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue';
import axios from "axios";
import type {AvgRow} from "@/types.ts";

const rows = ref<AvgRow[]>([]);

const currency = new Intl.NumberFormat(undefined, {
    style: 'currency',
    currency: 'USD',
    maximumFractionDigits: 2,
});

async function fetchSalaries(): Promise<void> {
    await axios.get<{ data: AvgRow[] }>('/api/company/salaries', {
        headers: {Accept: 'application/json'},
    }).then(({data: payload}) => {
        rows.value = Array.isArray(payload?.data) ? payload.data : [];
    });
}

onMounted(fetchSalaries);
defineExpose({fetchSalaries});
</script>