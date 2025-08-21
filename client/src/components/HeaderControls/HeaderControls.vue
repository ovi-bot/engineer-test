<template>
    <div class="header__button-container">
        <DownloadSampleCsvButton/>
        <ResetAppButton/>
    </div>
</template>

<script setup lang="ts">
import DownloadSampleCsvButton from "@/components/HeaderControls/DownloadSampleCsvButton.vue";
import ResetAppButton from "@/components/HeaderControls/ResetAppButton.vue";
</script>