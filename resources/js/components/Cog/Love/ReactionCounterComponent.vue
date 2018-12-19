<template>
    <span class="badge" v-text="count"></span>
</template>

<script>
    export default {
        props: {
            count: {
                type: Number,
                required: true,
            },
        },
    }
</script>
