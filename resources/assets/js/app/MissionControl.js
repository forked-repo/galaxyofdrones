import { EventBus } from './event-bus';
import Mission from './Mission';

export default {
    props: ['isEnabled', 'url', 'storeUrl'],

    components: {
        Mission
    },

    data() {
        return {
            data: {
                resources: [],
                missions: []
            }
        };
    },

    created() {
        EventBus.$on('planet-update', () => this.fetchData());
    },

    computed: {
        isEmpty() {
            return !this.data.missions.length;
        }
    },

    watch: {
        isEnabled() {
            this.fetchData();
        }
    },

    methods: {
        fetchData() {
            if (!this.isEnabled) {
                return;
            }

            axios.get(this.url).then(
                response => this.data = response.data
            );
        },

        isCompletable(mission) {
            return !_.some(mission.resources, resource => resource.quantity > _.find(this.data.resources, {
                id: resource.id
            }).quantity);
        },

        store(mission) {
            axios.post(
                this.storeUrl.replace('__mission__', mission.id)
            );
        }
    }
};
