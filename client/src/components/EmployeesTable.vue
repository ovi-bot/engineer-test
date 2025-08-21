<template>
    <table v-if="rows.length" class="table">
        <thead>
        <tr>
            <th class="table__header-cell">Company</th>
            <th class="table__header-cell">Name</th>
            <th class="table__header-cell">Email</th>
            <th class="table__header-cell table__cell--amount">Salary</th>
        </tr>
        </thead>
        <tbody>
        <EmployeesTableRow v-for="row in rows" :key="row.id" :row="row"/>
        </tbody>
    </table>

    <div v-else class="empty-message">No employees found.</div>
</template>
<script setup lang="ts">
import {onMounted, ref} from 'vue';
import axios from 'axios';
import type {EmployeeRow} from "@/types.ts";
import EmployeesTableRow from "@/components/EmployeesTableRow.vue";

const rows = ref<EmployeeRow[]>([]);

async function fetchEmployees(): Promise<void> {
    await axios.get<{ data: EmployeeRow[] }>('/api/employee/list', {
        headers: {Accept: 'application/json'},
    }).then(({data: payload}) => {
        rows.value = Array.isArray(payload?.data) ? payload.data : [];
    });
}

onMounted(fetchEmployees);
defineExpose({fetchEmployees});

</script>