/**
 * Intisalise un nouveau compteur de caractère pour chaque élément qui a l'attribut [data-maxlength]
 *
 * @return {null}
 */
function counter () {
  const containerElts = document.querySelectorAll('[data-maxlength]')

  containerElts.forEach(containerElt => {
    const maxlength = containerElt.dataset.maxlength
    const counterElt = counterInit(maxlength)
    const inputElt = containerElt.querySelector('[maxlength]')

    containerElt.append(counterElt)
    counterUpdate(counterElt, inputElt, maxlength)
  })
}

/**
 * Initialise un nouveau compteur de caractère
 *
 * @param {string|number} maxlength
 *
 * @return {null}
 */
function counterInit (maxlength) {
  const counterElt = document.createElement('div')
  counterElt.className = 'field is-size-7 has-text-right has-text-weight-bold p-1 input-counter'
  counterElt.innerHTML = '0/' + maxlength
  return counterElt
}
/**
 * Met à jour la valeur du compteur
 *
 * @param {HTMLDivElement} counterElt
 * @param {HTMLInputElement} inputElt
 * @param {string|number} maxLength
 *
 * @return {null}
 */
function counterUpdate (counterElt, inputElt, maxLength) {
  maxLength = Number(maxLength) // convert string to number
  // Listen to input event
  inputElt.addEventListener('input', () => {
    setCounterValue(counterElt, inputElt, maxLength)
  })

  if (inputElt.value.length) {
    setCounterValue(counterElt, inputElt, maxLength)
  }
}

function setCounterValue (counterElt, inputElt, maxLength) {
  const currentLength = inputElt.value.length
  if (currentLength >= maxLength) {
    counterElt.classList.add('has-text-danger')
  } else {
    counterElt.classList.remove('has-text-danger')
  }
  counterElt.innerHTML = `${currentLength}/${maxLength}`
}

window.addEventListener('DOMContentLoaded', () => {
  counter()
})
