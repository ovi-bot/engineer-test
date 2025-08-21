<template>
    <button class="header__button header__button--danger" @click="resetApp">
        Reset
    </button>
</template>

<script setup lang="ts">
import axios from "axios";

const resetApp = () => {
    if (confirm('Are you sure you want to reset the app?')) {
        axios.delete('/api/app/reset')
            .then(() => {
                alert('App reset! The window will reload.');
                window.location.reload();
            });
    }
}
</script>