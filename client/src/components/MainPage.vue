<template>
    <div class="main-page">
        <div class="main-page__upload">
            <FileUpload @uploaded="fetchLists"/>
            <AverageSalaries ref="avgRef"/>
        </div>
        <EmployeesTable ref="empRef"/>
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import FileUpload from "@/components/FileUpload.vue";
import AverageSalaries from "@/components/AverageSalaries.vue";
import EmployeesTable from "@/components/EmployeesTable.vue";

const avgRef = ref<InstanceType<typeof AverageSalaries> | null>(null);
const empRef = ref<InstanceType<typeof EmployeesTable> | null>(null);


function fetchLists() {
    avgRef.value?.fetchSalaries?.();
    empRef.value?.fetchEmployees?.();
}
</script>