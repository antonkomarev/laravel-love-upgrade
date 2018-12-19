<template>
    <button class="btn">
        <i class="fas fa-balance-scale"></i>
        <span class="badge" v-text="this.weight"></span>
    </button>
</template>

<script>
    export default {
        props: {
            weight: {
                type: Number,
                required: false,
            },
        },
    }
</script>
