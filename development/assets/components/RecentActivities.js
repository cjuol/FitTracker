export default {
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
    },
    template: `
        <div class="flex-1 overflow-y-auto p-4 space-y-4">
            <div v-for="activity in activities.slice(0, 6)" :key="activity.id" 
                class="bg-slate-800/30 border border-slate-700/30 p-4 rounded-2xl hover:bg-slate-800/50 transition-all cursor-pointer group">
                
                <div class="flex justify-between items-start mb-2">
                    <div :class="activity.type === 'strength' ? 'bg-blue-500' : 'bg-green-500'" class="w-1 h-4 rounded-full"></div>
                    <span class="text-[10px] font-mono text-slate-600 italic">
                        {{ formatDate(activity.createdAt) }}
                    </span>
                </div>

                <h4 class="text-sm font-bold text-slate-200 uppercase tracking-tight group-hover:text-blue-400 transition-colors">
                    {{ activity.type }}
                </h4>

                <div class="mt-2 flex flex-wrap gap-1">
                    <span v-for="item in activity.data.slice(0, 3)" :key="item.exercise || item.activity" 
                        class="text-[9px] bg-slate-950 text-slate-400 px-2 py-0.5 rounded border border-white/5">
                        {{ item.exercise || item.activity }}
                    </span>
                </div>
            </div>

            <div v-if="activities.length === 0" class="text-center py-10">
                <p class="text-slate-600 text-xs uppercase tracking-widest">No activities yet</p>
            </div>
        </div>
    `
};