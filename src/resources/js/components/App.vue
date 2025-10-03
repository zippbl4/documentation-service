<template>
    <div>
        <tooltip content="Это подсказка при наведении">
            <button>Наведите на меня</button>
        </tooltip>
    </div>
</template>

<script>
import Tooltip from './Tooltip.vue';

export default {
    mounted() {
        console.log('App mounted.')
    },
    components: {
        Tooltip,
    },
    methods: {
    },
};
</script>
