// Тест подбора варианта товара (skPick из sk.js). Запуск: node Stroy-krovlya26/redesign/tests/variants-test.js
const assert = require('assert');
const fs = require('fs');
const path = require('path');
const src = fs.readFileSync(path.join(__dirname, '../bitrix/bitrix/templates/shop/sk/js/sk.js'), 'utf8');
const body = src.slice(src.indexOf('function skPick'), src.indexOf('window.skPick'));
const skPick = new Function(body + 'return skPick;')();

const offers = [
	{ id: 1, coat: 'Atlas', thick: '0.5 мм', price: 796, colors: ['ral3005', 'ral8017'] },
	{ id: 2, coat: 'Drap', thick: '0.45 мм', price: 660, colors: ['ral9005'] },
	{ id: 3, coat: 'Drap', thick: '0.5 мм', price: 720, colors: ['ral9005', 'ral8017'] },
];

let r = skPick(offers, {});
assert.strictEqual(r.offer.id, 1, 'по умолчанию — первое предложение');
assert.deepStrictEqual(r.thicks, ['0.5 мм']);

r = skPick(offers, { coat: 'Drap' });
assert.strictEqual(r.offer.id, 2, 'Drap без толщины → первая толщина Drap');
assert.deepStrictEqual(r.thicks, ['0.45 мм', '0.5 мм']);

r = skPick(offers, { coat: 'Drap', thick: '0.5 мм', color: 'ral8017' });
assert.strictEqual(r.offer.id, 3);
assert.strictEqual(r.sel.color, 'ral8017', 'цвет есть у предложения — сохраняется');

r = skPick(offers, { coat: 'Atlas', thick: '0.45 мм', color: 'ral9005' });
assert.strictEqual(r.offer.id, 1, 'толщины нет у Atlas → берётся доступная');
assert.strictEqual(r.sel.color, '', 'цвета нет у предложения → сброс');

r = skPick([], {});
assert.strictEqual(r.offer, null, 'товар без вариантов');

console.log('variants-test: OK');
