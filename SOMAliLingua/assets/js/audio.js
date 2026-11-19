// Reusable audio helper for lesson examples and uploaded Somali recordings.
let somaliLinguaAudio = null;
function playSomaliAudio(source) {
  if (source && !/^(https?:)?\//i.test(source)) source = `${window.SOMALILINGUA_BASE || '/SomaliLingua'}/${source.replace(/^\//, '')}`;
  if (!source) { alert('No audio recording has been uploaded for this item yet.'); return; }
  if (somaliLinguaAudio) { somaliLinguaAudio.pause(); somaliLinguaAudio.currentTime = 0; }
  somaliLinguaAudio = new Audio(source);
  somaliLinguaAudio.play().catch(() => alert('The audio file could not be played. Check that it exists and is a supported audio format.'));
}
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-audio]').forEach(button => {
    if (!button.dataset.audio) return;
    button.addEventListener('click', () => playSomaliAudio(button.dataset.audio));
  });
  const tiles = document.querySelectorAll('[data-letter]');
  const detailLetter = document.querySelector('[data-detail-letter]');
  if (tiles.length && detailLetter) {
    const word = document.querySelector('[data-detail-word]');
    const meaning = document.querySelector('[data-detail-meaning]');
    const pronunciation = document.querySelector('[data-detail-pronunciation]');
    const play = document.querySelector('[data-detail-play]');
    const mark = document.querySelector('[data-mark-progress][data-item-type="alphabet"]');
    tiles.forEach(tile => tile.addEventListener('click', () => {
      tiles.forEach(t => t.classList.remove('selected')); tile.classList.add('selected');
      detailLetter.textContent = tile.dataset.letter; word.textContent = tile.dataset.example || 'Example pending';
      meaning.textContent = tile.dataset.meaning || 'Meaning pending'; pronunciation.textContent = tile.dataset.pronunciation || 'Pronunciation guide to be added.';
      const audio = tile.dataset.audio ? `${window.SOMALILINGUA_BASE || '/SomaliLingua'}/${tile.dataset.audio}` : '';
      play.disabled = !audio; play.onclick = () => playSomaliAudio(audio);
      if (mark) { mark.dataset.itemId = tile.dataset.letterId || tile.dataset.id || ''; mark.disabled = !mark.dataset.itemId; }
    }));
    if (tiles[0]) tiles[0].click();
  }
});
