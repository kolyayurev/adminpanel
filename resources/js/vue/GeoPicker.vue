<template>
    <div class="geo-picker">
        <el-input v-model="localValue.coords" :placeholder="placeholder">
            <template #append>
                <el-button @click="openDialog"><i class="bi bi-pin-map"></i></el-button>
            </template>
        </el-input>
        <el-dialog
            class="dialog --form"
            v-model="dialogVisibility"
            :title="title"
            top="10vh"
            draggable
            @opened="onOpened"
            @closed="onClosed"
        >
            <div class="geo-picker__map" id="geoPickerMap"></div>
            <template #footer>
                <el-button @click="onSave" type="primary">{{ lang.get('common.save') }}</el-button>
            </template>
        </el-dialog>
    </div>
</template>

<script>
import {dialog} from '../mixins/dialog'
import ymaps from 'ymaps';

var map = null;

// TODO: for arraybuilder

export default {
    mixins: [dialog],
    name: "v-geo-picker",
    props: {
        placeholder: String,
        apiKey:String,
        center:{
            type: Array,
            default: [59.93499,30.31907]
        },
        zoom:{
            type: Number,
            default: 8
        },
        modelValue: {
            validator: v => true,
            required: true,
        },
    },
    computed: {
        localValue: {
            get() {
                return _.isNull(this.modelValue) ? { coords:[],address:''} : this.modelValue;
            },
            set(localState) {
                this.$emit('update:modelValue', localState);
            },
        },
    },
    data(){
        return{
            map: null,
        }
    },
    methods:{
        initMap(){
            let _this = this;
            ymaps
                .load('https://api-maps.yandex.ru/2.1/?apikey='+_this.apiKey+'&lang=ru_RU')
                .then(maps => {
                    let coords = _this.localValue.coords ?? _this.center;

                    map = new maps.Map('geoPickerMap', {
                        center: coords,
                        zoom: _this.zoom,
                        controls: [
                            'zoomControl',
                            'searchControl'
                        ],
                        zoomMargin: [20],
                    });

                    let Placemark = new maps.Placemark(coords, {
                        iconCaption: ''
                    }, {
                        preset: 'islands#violetDotIconWithCaption',
                        draggable: true
                    });

                    map.controls.get('searchControl').options.set({
                        noPopup: true,
                        noPlacemark: true,
                        kind: 'house'
                    });

                    // Создадим объекты на основе JSON-описания геометрий.
                    if (_this.localValue.coords) {
                        map.geoObjects.add(Placemark);
                    }

                    // Слушаем клик на карте.
                    map.events.add('click', function (e) {
                        let coords = e.get('coords'),
                            myReverseGeocoder = maps.geocode([coords[0], coords[1]]);

                        _this.localValue.coords = coords;
                        myReverseGeocoder.then(function (res) {
                            let geoObject = res.geoObjects.get(0);
                            let postalCode = geoObject.properties.get('metaDataProperty').GeocoderMetaData.Address.postal_code;
                            let address = typeof (postalCode) !== 'undefined' ? postalCode + ', ' + geoObject.properties.get('text') : geoObject.properties.get('text');
                            _this.localValue.address = address;
                        });

                        Placemark.geometry.setCoordinates(coords);
                        map.geoObjects.add(Placemark);
                    });
                })
                .catch(error => console.log('Failed to load Yandex Maps', error));
        },
        onOpened(){
            this.initMap();
        },
        onClosed(){
            map.destroy();
        },
        onSave(){
            this.closeDialog()
        }
    }
}
</script>

