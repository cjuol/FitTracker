import './styles/app.css';
import { createApp, ref, onMounted } from 'vue/dist/vue.esm-browser.js';
import RecentActivities from './components/RecentActivities.js';
import ActivityForm from './components/ActivityForm.js';

const app = createApp({
    components: {
        RecentActivities,
        ActivityForm
    },
    setup() {
        const activities = ref([]);
        const showForm = ref(false);

        const fetchActivities = async () => {
            const response = await fetch('/api/activities/');
            activities.value = await response.json();
        };

        

        const onActivitySaved = async () => {
            await fetchActivities();
            showForm.value = false;
        };

        onMounted(fetchActivities);

        return { activities, showForm, onActivitySaved };
    }
});

app.mount('#activity-app');