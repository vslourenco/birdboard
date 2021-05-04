<template>
    <div class="flex items-center mr-8">
        <div :key=theme v-for="(color, theme) in themes"
        class="rounded-full w-4 h-4 bg-default border mr-2 focus:outline-none"
        :class="{'border-accent': selectedTheme == theme }"
        :style="{ backgroundColor: color }"
        @click="selectedTheme=theme"
        ></div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            selectedTheme: 'theme-light',
            themes: {
                'theme-light': '#F5F6F9',
                'theme-dark': '#222'
            }
        }
    },
    created(){
        this.selectedTheme = localStorage.getItem('theme') || 'theme-light';
    },
    watch: {
        selectedTheme(){
            document.body.className = document.body.className.replace(/theme-\w+/, this.selectedTheme);
            localStorage.setItem('theme', this.selectedTheme);
        }
    }
}
</script>

