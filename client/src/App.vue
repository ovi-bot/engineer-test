<template>
    <header class="header">
        <h1 class="header__title">Engineer Test</h1>

        <HeaderControls/>
    </header>

    <main id="app-main">
        <MainPage/>
    </main>
</template>

<script setup lang="ts">
import MainPage from "@/components/MainPage.vue";
import HeaderControls from "@/components/HeaderControls/HeaderControls.vue";
</script>