import { ref } from 'vue';

export default {
    template: '#activity-form-template',
    emits: ['saved', 'close'],
    setup(props, { emit }) {
        const localType = ref('strength');
        const localContent = ref('');
        const isSaving = ref(false);

        const submitForm = async () => {
            if (!localContent.value.trim()) return;
            isSaving.value = true;

            try {
                const response = await fetch('/api/activities', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        type: localType.value, 
                        content: localContent.value 
                    })
                });

                if (response.ok) {
                    localContent.value = '';
                    emit('saved'); // Avisa al padre para refrescar la lista
                }
            } catch (e) {
                console.error("Error al guardar:", e);
            } finally {
                isSaving.value = false;
            }
        };

        return { localType, localContent, isSaving, submitForm };
    }
};