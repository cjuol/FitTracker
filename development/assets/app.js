import './styles/app.css';
import { createApp } from 'vue/dist/vue.esm-browser.js';
import ActivityApp from './components/RecentActivities.js'; // Cambiado a .js

const app = createApp({
    components: {
        RecentActivities // Registramos el componente
    },
    setup() {
        const activities = ref([]);
        const showForm = ref(false);

        const fetchActivities = async () => {
            const response = await fetch('/api/activities');
            activities.value = await response.json();
        };

        

        onMounted(fetchActivities);

        return { activities, showForm };
    }
});

app.mount('#activity-app');