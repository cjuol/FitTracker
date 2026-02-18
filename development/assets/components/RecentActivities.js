export default {
    template: '#recent-activities-template',
    props: {
        activities: {
            type: Array,
            required: true
        }
    },
    setup(props) {
        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES', {
                day: '2-digit',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        return { formatDate };
    }
};