<template>
    <button :class="this.buttonClass" v-on:click="toggleReaction()">
        <i :class="this.activeIcon" v-if="this.isReacted" v-text="this.activeText"></i>
        <i :class="this.inactiveIcon" v-else="this.isReacted" v-text="this.inactiveText"></i>

        <cog-love-reaction-counter-component
            :count="this.reactionCount"
        ></cog-love-reaction-counter-component>
    </button>
</template>

<script>
    export default {
        props: {
            uri: {
                type: String,
                required: true,
            },
            isReacted: {
                type: Boolean,
                required: true,
            },
            reactionCount: {
                type: Number,
                required: false,
            },
            buttonClass: {
                type: String,
                required: true,
            },
            activeIcon: {
                type: String,
                required: true,
            },
            inactiveIcon: {
                type: String,
                required: true,
            },
            activeText: {
                type: String,
                required: true,
            },
            inactiveText: {
                type: String,
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
            toggleReaction() {
                this.isReacted ? this.unreact() : this.react();
            },

            react() {
                axios.post(this.$props.uri).then(() => {
                    this.state = true;
                });
            },

            unreact() {
                axios.delete(this.$props.uri).then(() => {
                    this.state = false;
                });
            },
        },
    }
</script>
