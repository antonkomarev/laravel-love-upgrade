<template>
    <button class="btn">
        <i class="fas fa-burn"></i>
        <span class="badge" v-text="this.count"></span>

        <i class="fas fa-balance-scale"></i>
        <span class="badge" v-text="this.weight"></span>
    </button>
</template>

<script>
    export default {
        props: {
            count: {
                type: Number,
                required: false,
            },
            weight: {
                type: Number,
                required: false,
            },
        },
    }
</script>
