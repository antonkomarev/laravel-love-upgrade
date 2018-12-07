<template>
    <span class="badge badge-info" v-text="count"></span>
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
