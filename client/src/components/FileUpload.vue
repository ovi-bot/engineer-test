<template>
    <div class="upload-container">
        <form @submit.prevent="uploadFile">
            <input
                ref="fileInput"
                type="file"
                name="file"
                @change="onFileChange"
                :disabled="uploading"
                accept="*/*"
            />
            <button type="submit" :disabled="!file || uploading">
                {{ uploading ? 'Uploading…' : 'Upload' }}
            </button>
        </form>

        <p v-if="error || success" :class="{error:error, success:success}">{{ error || success }}</p>
    </div>
</template>
<script setup lang="ts">
import {ref} from 'vue';
import axios from 'axios';

const emit = defineEmits<{
    (e: 'uploaded', summary?: unknown): void
}>();

const fileInput = ref<HTMLInputElement | null>(null);
const file = ref<File | null>(null);
const uploading = ref(false);
const error = ref<string | null>(null);
const success = ref<string | null>(null);

function onFileChange(e: Event) {
    const input = e.target as HTMLInputElement;
    file.value = input.files && input.files[0] ? input.files[0] : null;
    error.value = null;
    success.value = null;
}

function resetFileSelection() {
    if (fileInput.value) fileInput.value.value = '';

    file.value = null;
}

async function uploadFile() {
    if (!file.value || uploading.value) return;

    error.value = null;
    success.value = null;
    uploading.value = true;

    const form = new FormData();
    form.append('file', file.value);

    try {
        const {data: payload} = await axios.post('/api/csv/upload', form, {
            headers: {Accept: 'application/json'},
        });

        success.value = payload?.message ?? 'File uploaded successfully.';
        emit('uploaded', payload?.summary ?? null);
    } catch (e: any) {
        const status = e?.response?.status ?? 'network';
        error.value = e?.response?.data?.message ?? `Upload failed (${status}).`;
    } finally {
        uploading.value = false;
        resetFileSelection();
    }
}
</script>
<style scoped>
.upload-container {
    display: grid;
    gap: 12px;
    max-width: 520px;
}

button[disabled] {
    opacity: 0.6;
    cursor: not-allowed;
}

.error {
    color: #b91c1c; /* red-700 */
}

.success {
    color: #166534; /* green-800 */
}
</style>