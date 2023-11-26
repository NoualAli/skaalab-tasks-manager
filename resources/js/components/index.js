import Child from './Child.vue'
import Alert from './Alert.vue'
import InputContainer from './Inputs/InputContainer.vue'
import NLInput from './Inputs/NLInput.vue'
import NLTextarea from './Inputs/NLTextarea.vue'
import NLButton from './Inputs/NLButton.vue'
import NLRadio from './Inputs/NLRadio.vue'
import NLRadios from './Inputs/NLRadios.vue'
import NLCheckboxes from './Inputs/NLCheckboxes.vue'
import NLCheckbox from './Inputs/NLCheckbox.vue'
import NLSelect from './Inputs/NLSelect'
import NLCheckableContainer from './Inputs/NLCheckableContainer.vue'
import NLWysiwyg from './Inputs/NLWysiwyg.vue'
import NLDatatable from './Datatable/NLDatatable.vue'
import { HasError, AlertErrors } from 'vform/components/bootstrap5'
import NLModal from './NLModal'
import ContentHeader from './ContentHeader'
import ContentBody from './ContentBody'
import NLRepeater from './Inputs/NLRepeater'
import NLSwitch from './Inputs/NLSwitch'
import NLFile from './Inputs/NLFile'
import NLContainer from './NLContainer'
import NLHeading from './NLHeading'
import NLGrid from './Grid/NLGrid'
import NLColumn from './Grid/NLColumn'
import NLFlex from './Grid/NLFlex'
import NLForm from './NLForm'

// Components that are registered globaly.
export function useComponents(app) {
    [
        // NLPageLoader,
        // NLComponentLoader
        NLForm,
        NLRadios,
        NLCheckboxes,
        NLGrid,
        NLColumn,
        NLFlex,
        NLContainer,
        NLHeading,
        NLFile,
        NLSwitch,
        NLRepeater,
        ContentBody,
        ContentHeader,
        NLSelect,
        NLModal,
        NLTextarea,
        InputContainer,
        NLDatatable,
        NLCheckableContainer,
        NLCheckbox,
        NLRadio,
        NLButton,
        NLWysiwyg,
        NLInput,
        // Card,
        Child,
        // Button,
        // Checkbox,
        HasError,
        // AlertError,
        // AlertSuccess,
        Alert,
        AlertErrors
    ].forEach(Component => {
        app.component(Component.name, Component)
    })
}
