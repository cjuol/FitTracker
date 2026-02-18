import { ref, computed } from 'vue/dist/vue.esm-browser.js';

export default {
    template: '#activity-form-template',
    emits: ['saved', 'close'],
    setup(props, { emit }) {
        const localType = ref('strength');
        const isSaving = ref(false);

        const strengthSets = ref([
            { exercise: '', reps: 0, rir: 0, weight: 0, note: '' }
        ]);

        const cardioSets = ref([
            { activityName: '', distanceKm: 0, durationMinutes: 0, avgHeartRate: null, note: '' }
        ]);

        const currentSets = computed(() => (
            localType.value === 'strength' ? strengthSets.value : cardioSets.value
        ));

        const addSet = () => {
            if (localType.value === 'strength') {
                strengthSets.value.push({ exercise: '', reps: 0, rir: 0, weight: 0, note: '' });
            } else {
                cardioSets.value.push({ activityName: '', distanceKm: 0, durationMinutes: 0, avgHeartRate: null, note: '' });
            }
        };

        const removeSet = (index) => {
            if (localType.value === 'strength') {
                if (strengthSets.value.length > 1) strengthSets.value.splice(index, 1);
            } else {
                if (cardioSets.value.length > 1) cardioSets.value.splice(index, 1);
            }
        };

        const adjustValue = (set, field, step) => {
            const current = Number(set[field] ?? 0);
            const next = Math.round((current + step) * 100) / 100;
            set[field] = next;
        };

        const submitForm = async () => {
            const setsPayload = (localType.value === 'strength'
                ? strengthSets.value
                : cardioSets.value
            ).map((set) => ({
                ...set,
                avgHeartRate: set.avgHeartRate === '' ? null : set.avgHeartRate,
                note: set.note || null,
            }));

            if (!setsPayload.length) return;
            isSaving.value = true;

            try {
                const response = await fetch('/api/activities/create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        type: localType.value,
                        sets: setsPayload
                    })
                });

                if (response.ok) {
                    if (localType.value === 'strength') {
                        strengthSets.value = [{ exercise: '', reps: 0, rir: 0, weight: 0, note: '' }];
                    } else {
                        cardioSets.value = [{ activityName: '', distanceKm: 0, durationMinutes: 0, avgHeartRate: null, note: '' }];
                    }
                    emit('saved'); // Avisa al padre para refrescar la lista
                }
            } catch (e) {
                console.error("Error al guardar:", e);
            } finally {
                isSaving.value = false;
            }
        };

        return {
            localType,
            isSaving,
            strengthSets,
            cardioSets,
            currentSets,
            addSet,
            removeSet,
            adjustValue,
            submitForm
        };
    }
};