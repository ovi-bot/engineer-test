<template>
    <tr>
        <td class="table__cell">{{ row.company_name }}</td>
        <td class="table__cell">{{ row.name }}</td>
        <td class="table__cell">
            <template v-if="isEditing">
                <input
                    v-model="draftEmail"
                    type="email"
                    class="input"
                    @keyup.enter="confirmEdit"
                    @keyup.esc="cancelEdit"
                />
                <button class="btn btn--confirm" @click="confirmEdit">Confirm</button>
                <button class="btn btn--cancel" @click="cancelEdit">Cancel</button>
            </template>
            <template v-else>
                <button class="icon-button" title="Edit email" @click="startEdit">✎</button>
                {{ row.email }}
            </template>

        </td>
        <td class="table__cell table__cell--amount">
            {{ currency.format(row.salary) }}
        </td>
    </tr>
</template>
<script setup lang="ts">
import {ref} from 'vue';
import axios from 'axios';

const {row} = defineProps({
    row: {
        type: Object,
        required: true
    }
});

const isEditing = ref(false);
const draftEmail = ref<string>(row.email);

function startEdit(): void {
    draftEmail.value = row.email;
    isEditing.value = true;
}

function cancelEdit(): void {
    draftEmail.value = row.email;
    isEditing.value = false;
}

async function confirmEdit(): Promise<void> {
    const newEmail = (draftEmail.value || '').trim();
    if (!newEmail || newEmail === row.email) {
        isEditing.value = false;
        return;
    }

    const form = new URLSearchParams();
    form.append('email', newEmail);

    await axios.post('/api/employee/updateEmail/' + row.id, form, {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            Accept: 'application/json'
        }
    }).then(() => {
        row.email = newEmail;
        isEditing.value = false;
    }).catch((e: any) => {
        alert(e.response?.data?.message || 'Failed to update email.');
    });
}

const currency = new Intl.NumberFormat(undefined, {
    style: 'currency',
    currency: 'USD',
    maximumFractionDigits: 0,
});
</script>