<template>
    <button style="cursor: pointer;" class="btn btn-link" v-on:click="toggleLike()">
        <i class="fas fa-heart" v-if="this.liked">Unlike</i>
        <i class="far fa-heart" v-else="this.liked">Like</i>
    </button>
</template>

<script>
    export default {
        props: {
            uri: {
                type: String,
                required: true,
            },
            isLiked: {
                type: Boolean,
                required: true,
            },
        },
        data() {
            return {
                state: null,
            };
        },
        computed: {
            liked() {
                return this.state !== null ? this.state : this.$props.isLiked;
            },
        },
        methods: {
            toggleLike() {
                this.liked ? this.dislike() : this.like();
            },

            like() {
                axios.post(this.$props.uri).then(() => {
                    this.state = true;
                });
            },

            dislike() {
                axios.delete(this.$props.uri).then(() => {
                    this.state = false;
                });
            },
        },
    }
</script>
