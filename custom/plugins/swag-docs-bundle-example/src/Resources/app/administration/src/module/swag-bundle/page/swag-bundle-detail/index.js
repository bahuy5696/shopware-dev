// import template from './swag-bundle-detail.html.twig';
//
// const { Component, Mixin } = Shopware;
//
// Component.register('swag-bundle-detail', {
//     template,
//
//     inject: [
//         'repositoryFactory'
//     ],
//
//     mixins: [
//         Mixin.getByName('notification')
//     ],
//
//     metaInfo() {
//         return {
//             title: this.$createTitle()
//         };
//     },
//
//     data() {
//         return {
//             bundle: null,
//             isLoading: false,
//             processSuccess: false,
//             repository: null
//         };
//     },
//
//     computed: {
//         options() {
//             return [
//                 { value: 'absolute', name: this.$t('swag-bundle.detail.absoluteText') },
//                 { value: 'percentage', name: this.$t('swag-bundle.detail.percentageText') }
//             ];
//         }
//     },
//
//     created() {
//         this.repository = this.repositoryFactory.create('swag_bundle');
//         this.getBundle();
//     },
//
//     methods: {
//         getBundle() {
//             this.repository
//                 .get(this.$route.params.id, Shopware.Context.api)
//                 .then((entity) => {
//                     this.bundle = entity;
//                 });
//         },
//
//         onClickSave() {
//             this.isLoading = true;
//
//             this.repository
//                 .save(this.bundle, Shopware.Context.api)
//                 .then(() => {
//                     this.getBundle();
//                     this.isLoading = false;
//                     this.processSuccess = true;
//                 }).catch((exception) => {
//                     this.isLoading = false;
//                     this.createNotificationError({
//                         title: this.$t('swag-bundle.detail.errorTitle'),
//                         message: exception
//                     });
//                 });
//         },
//
//         saveFinish() {
//             this.processSuccess = false;
//         }
//     }
// });
import template from './swag-bundle-detail.html.twig'

const {Component, Mixin, Context} = Shopware;

Component.register('swag-bundle-detail', {
    template,
    inject: [
        'repositoryFactory'
    ],
    mixins: [
        Mixin.getByName('notification')
    ],
    metaInfo() {
        return {
            title: this.$createTitle()
        }
    },
    data() {
        return {
            bundle: null,
            isLoading: false,
            isSuccess: false,
            repository: null,
        }
    },
    computed: {
        options() {
            return [
                {value: 'absolute', name: this.$tc('swag-bundle.detail.absoluteText')},
                {value: 'percentage', name: this.$tc('swag-bundle.detail.percentageText')}
            ]

        }
    },
    created() {
        this.createdComponent();
    },
    methods: {
        createdComponent() {
            this.repository = this.repositoryFactory.create('swag_bundle');
            this.getBundle();
        },
        getBundle() {
            this.repository.get(this.$route.params.id, Context.api).then((entity) => {
                this.bundle = entity;
            })
        },
        onClickSave(){
            this.isLoading = true;
            this.repository.save(this.bundle,Context.api).then(()=>{
                this.getBundle();
                this.isLoading = false;
                this.isSuccess = true;
            }).catch(e => {
                this.isLoading = false;
                this.createNotificationError({
                    title: this.$tc('swag-bundle.detail.errorTitle'),
                    message: e
                })
            })
        },
        saveFinish(){
            this.isSuccess = false;
        }
    }
})
