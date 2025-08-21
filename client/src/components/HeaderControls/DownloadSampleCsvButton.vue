<template>
    <button class="header__button" @click="downloadSampleCsv">
        Download Sample CSV
    </button>
</template>
<script setup lang="ts">
const downloadSampleCsv = () => {
    const link = document.createElement('a');

    link.href = '/sample.csv';
    link.download = 'sample.csv';

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
