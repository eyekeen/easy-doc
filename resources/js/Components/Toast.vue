<template>
    <div v-if="visible" :class="[
        'fixed top-5 right-5 max-w-xs w-full p-4 rounded-lg shadow-lg transition-opacity duration-300',
        toastTypeClass,
        { 'opacity-0': !visible, 'opacity-100': visible }
    ]" @mouseenter="pauseTimer" @mouseleave="startTimer">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <!-- Icon based on type -->
                <component :is="iconComponent" class="w-6 h-6"></component>
            </div>
            <div class="ml-3 w-0 flex-1">
                <p class="text-sm font-medium">
                    {{ message }}
                </p>
            </div>
            <div class="ml-4 flex-shrink-0 flex">
                <button @click="close" class="text-gray-500 hover:text-gray-700">
                    ✖️
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Toast',
    props: {
        message: {
            type: String,
            required: true,
        },
        type: {
            type: String,
            default: 'success', // 'success' or 'error'
        },
        duration: {
            type: Number,
            default: 3000, // Duration in milliseconds
        },
    },
    data() {
        return {
            visible: false,
            timer: null,
        };
    },
    computed: {
        toastTypeClass() {
            return this.type === 'success'
                ? 'bg-green-100 text-green-700'
                : 'bg-red-100 text-red-700';
        },
        iconComponent() {
            return this.type === 'success'
                ? 'SuccessIcon'
                : 'ErrorIcon';
        },
    },
    methods: {
        show() {
            this.visible = true;
            this.startTimer();
        },
        close() {
            this.visible = false;
            this.clearTimer();
        },
        startTimer() {
            this.clearTimer();
            this.timer = setTimeout(() => {
                this.close();
            }, this.duration);
        },
        pauseTimer() {
            this.clearTimer();
        },
        clearTimer() {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
        },
    },
    components: {
        SuccessIcon: {
            template: '<svg class="w-6 h-6 text-green-500" fill="currentColor"><path d="..."/></svg>',
        },
        ErrorIcon: {
            template: '<svg class="w-6 h-6 text-red-500" fill="currentColor"><path d="..."/></svg>',
        },
    },
    mounted() {
        this.show();
    },
};
</script>


<style scoped>
/* Transition styles if needed */
</style>