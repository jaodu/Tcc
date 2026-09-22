create database bd_vitallis;

use bd_vitallis;

create table usuario(
id integer primary key auto_increment,
email varchar(100) not null unique,
senha varchar(255) not null
);

create table perfil(
id integer primary key auto_increment,
nome varchar(100) not null,
data_nascimento date not null,
peso decimal(5,2) not null,
id_usuario integer not null unique,
foreign key (id_usuario) references usuario(id)
);



create table regiao_corpo(
id integer primary key auto_increment,
nome varchar(50) not null
);

create table registro_dor(
id integer primary key auto_increment,
data_registro date not null,
ativa boolean default true,
id_usuario integer not null,
id_regiao_corpo integer not null,
foreign key (id_usuario) references usuario(id),
foreign key (id_regiao_corpo) references regiao_corpo(id)
);

create table exercicio(
id integer primary key auto_increment,
titulo varchar(100) not null,
repeticao text not null,
cuidados text not null,
como_fazer text not null,
descricao text not null,
id_regiao_corpo integer not null,
foreign key (id_regiao_corpo) references regiao_corpo(id)
);

create table cronograma(
id integer primary key auto_increment,
dia int not null,
id_exercicio integer not null,
id_regiao_corpo integer not null,
foreign key (id_exercicio) references exercicio(id),
foreign key (id_regiao_corpo) references regiao_corpo(id)
);

create table atividade_usuario(
id integer primary key auto_increment,
data_atividade date not null,
concluido boolean default false,
id_usuario integer not null,
id_exercicio integer not null,
foreign key (id_usuario) references usuario(id),
foreign key (id_exercicio) references exercicio(id)
);

create table evolucao(
id integer primary key auto_increment,
data_registro date not null,
bem_estar varchar(20) not null,
progresso int not null,
id_usuario integer not null,
foreign key (id_usuario) references usuario(id)
);

insert into regiao_corpo (nome) values 
('Cabeça'),
('Pescoço'),
('Ombro'),
('Braço'),
('Mãos'),
('Peito'),
('Abdômen'),
('Coluna'),
('Quadril'),
('Coxa'),
('Joelho'),
('Canela'),
('Pés');

insert into exercicio (titulo, repeticao, cuidados, como_fazer, descricao, id_regiao_corpo) values

(
'Inclinação lateral do pescoço',
'1. Repetir 10 vezes para cada lado.
2. Manter o alongamento por 15 segundos.
3. Realizar 2 séries.',
'1. Não forçar além do limite confortável.
2. Evitar movimentos bruscos.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Sente-se com a coluna ereta.
2. Relaxe os ombros.
Movimento
1. Incline lentamente a cabeça em direção ao ombro direito.
2. Mantenha a posição.
3. Retorne ao centro.
4. Repita para o lado esquerdo.',
'Alonga os músculos do pescoço, reduz a tensão muscular e melhora a flexibilidade da região cervical.',
2
),

(
'Rotação cervical',
'1. Repetir 10 vezes para cada lado.
2. Executar lentamente.
3. Realizar 2 séries.',
'1. Evitar girar rapidamente a cabeça.
2. Manter os ombros relaxados.
3. Parar caso sinta tontura ou dor intensa.',
'Posição inicial
1. Sente-se confortavelmente.
Movimento
1. Gire lentamente a cabeça para a direita.
2. Mantenha por alguns segundos.
3. Retorne ao centro.
4. Repita para o lado esquerdo.',
'Melhora a mobilidade da coluna cervical, reduz a rigidez e auxilia no alívio de dores no pescoço.',
2
),

(
'Flexão e extensão cervical',
'1. Repetir 10 vezes.
2. Fazer 2 séries.
3. Executar lentamente.',
'1. Não realizar movimentos bruscos.
2. Respeitar o limite de movimento.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Sente-se com a coluna reta.
Movimento
1. Incline lentamente a cabeça para frente.
2. Retorne à posição inicial.
3. Incline a cabeça para trás suavemente.
4. Retorne ao centro.',
'Ajuda a melhorar a mobilidade do pescoço, reduz a rigidez muscular e favorece a postura.',
2
),

(
'Retração cervical',
'1. Repetir 10 vezes.
2. Manter cada repetição por 5 segundos.
3. Fazer 2 séries.',
'1. Não inclinar a cabeça durante o movimento.
2. Executar lentamente.
3. Suspender caso provoque dor intensa.',
'Posição inicial
1. Sente-se com a coluna ereta.
2. Mantenha o olhar para frente.
Movimento
1. Puxe o queixo para trás, como se estivesse formando uma "papada".
2. Mantenha por 5 segundos.
3. Relaxe lentamente.',
'Fortalece a musculatura cervical profunda, melhora a postura e ajuda a aliviar dores causadas pelo uso prolongado de computadores e celulares.',
2
),

(
'Elevação e relaxamento dos ombros',
'1. Repetir 15 vezes.
2. Fazer 2 séries.
3. Respirar normalmente durante o exercício.',
'1. Não elevar os ombros com força excessiva.
2. Manter os movimentos lentos.
3. Interromper caso sinta desconforto intenso.',
'Posição inicial
1. Sente-se ou permaneça em pé com a coluna reta.
Movimento
1. Eleve os ombros em direção às orelhas.
2. Mantenha por 3 segundos.
3. Relaxe completamente os ombros.
4. Repita o movimento.',
'Reduz a tensão da musculatura do pescoço e dos ombros, melhora a circulação e promove relaxamento muscular.',
2
),

(
'Rotação dos ombros',
'1. Fazer 10 rotações para frente.
2. Fazer 10 rotações para trás.
3. Repetir 2 séries.',
'1. Realizar movimentos lentos.
2. Não elevar os ombros com força.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Fique em pé ou sentado com a coluna ereta.
2. Relaxe os braços ao lado do corpo.
Movimento
1. Gire os ombros lentamente para frente.
2. Complete as repetições.
3. Inverta o sentido realizando rotações para trás.',
'Melhora a mobilidade dos ombros, reduz a rigidez muscular e ajuda no aquecimento da articulação.',
3
),

(
'Alongamento cruzado do ombro',
'1. Manter por 20 segundos.
2. Repetir 3 vezes em cada braço.',
'1. Não puxar o braço com força.
2. Manter a respiração normal.
3. Suspender caso provoque dor intensa.',
'Posição inicial
1. Fique em pé ou sentado.
Movimento
1. Leve um braço estendido à frente do peito.
2. Com a outra mão, aproxime suavemente o braço do corpo.
3. Mantenha o alongamento.
4. Repita com o outro braço.',
'Alonga a musculatura posterior do ombro, melhora a flexibilidade e reduz tensões musculares.',
3
),

(
'Elevação frontal dos braços',
'1. Fazer 10 repetições.
2. Repetir 2 séries.',
'1. Não elevar os braços além do limite confortável.
2. Manter os movimentos controlados.
3. Parar caso sinta dor intensa.',
'Posição inicial
1. Permaneça em pé com os braços ao lado do corpo.
Movimento
1. Eleve lentamente os braços à frente até a altura dos ombros.
2. Retorne devagar à posição inicial.
3. Repita o movimento.',
'Melhora a mobilidade dos ombros, fortalece a musculatura e auxilia na recuperação funcional.',
3
),

(
'Deslizamento na parede',
'1. Fazer 10 repetições.
2. Repetir 2 séries.',
'1. Não forçar caso haja limitação de movimento.
2. Executar lentamente.
3. Manter a postura correta durante o exercício.',
'Posição inicial
1. Fique de frente para uma parede.
2. Apoie as mãos na parede.
Movimento
1. Deslize lentamente as mãos para cima.
2. Vá até onde for confortável.
3. Retorne lentamente à posição inicial.',
'Favorece a mobilidade do ombro, melhora a amplitude de movimento e auxilia na recuperação da articulação.',
3
),

(
'Aproximação das escápulas',
'1. Fazer 15 repetições.
2. Repetir 2 séries.',
'1. Não elevar os ombros durante o exercício.
2. Manter a coluna ereta.
3. Evitar movimentos bruscos.',
'Posição inicial
1. Sente-se ou fique em pé com os braços relaxados.
Movimento
1. Puxe os ombros para trás.
2. Aproxime as escápulas como se quisesse uni-las.
3. Mantenha por 5 segundos.
4. Relaxe lentamente.',
'Fortalece os músculos responsáveis pela estabilização dos ombros, melhora a postura e reduz dores causadas por tensão muscular.',
3
),

(
'Flexão de cotovelo',
'1. Fazer 10 a 15 repetições.
2. Repetir de 2 a 3 séries.
3. Executar lentamente.',
'1. Não realizar movimentos bruscos.
2. Respeitar o limite de movimento.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Fique em pé ou sentado com os braços ao lado do corpo.
Movimento
1. Dobre lentamente o cotovelo aproximando a mão do ombro.
2. Retorne devagar à posição inicial.
3. Repita o movimento.',
'Melhora a mobilidade do cotovelo, fortalece a musculatura do braço e auxilia na recuperação dos movimentos.',
4
),

(
'Extensão de cotovelo',
'1. Fazer 10 repetições.
2. Repetir 2 séries.
3. Descansar 30 segundos entre as séries.',
'1. Não forçar a extensão completa caso haja dor.
2. Executar lentamente.
3. Manter a postura correta.',
'Posição inicial
1. Sente-se confortavelmente.
2. Mantenha o braço dobrado.
Movimento
1. Estenda lentamente o cotovelo.
2. Retorne à posição inicial.
3. Repita o exercício.',
'Ajuda a recuperar a amplitude de movimento do cotovelo, melhora a mobilidade e fortalece a musculatura do braço.',
4
),

(
'Alongamento do bíceps',
'1. Permanecer por 20 segundos.
2. Repetir 3 vezes em cada braço.',
'1. Não exagerar no alongamento.
2. Manter a respiração tranquila.
3. Suspender caso provoque dor intensa.',
'Posição inicial
1. Fique em pé.
2. Estenda o braço para trás com a palma da mão voltada para baixo.
Movimento
1. Alongue suavemente o braço.
2. Mantenha a posição.
3. Repita com o outro braço.',
'Alonga a musculatura anterior do braço, melhora a flexibilidade e reduz tensões musculares.',
4
),

(
'Alongamento do tríceps',
'1. Permanecer por 20 segundos.
2. Repetir 3 vezes em cada braço.',
'1. Não puxar o braço com força.
2. Manter os movimentos suaves.
3. Interromper caso sinta desconforto intenso.',
'Posição inicial
1. Levante um braço acima da cabeça.
Movimento
1. Dobre o cotovelo levando a mão em direção às costas.
2. Com a outra mão, empurre suavemente o cotovelo.
3. Mantenha o alongamento.
4. Repita no outro braço.',
'Melhora a flexibilidade do tríceps, reduz a rigidez muscular e auxilia na recuperação dos movimentos do braço.',
4
),

(
'Pronação e supinação do antebraço',
'1. Fazer 10 repetições.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Manter o cotovelo junto ao corpo.
2. Não realizar movimentos bruscos.
3. Parar caso sinta dor intensa.',
'Posição inicial
1. Sente-se com o cotovelo apoiado a 90 graus.
Movimento
1. Gire a palma da mão para cima.
2. Em seguida, gire a palma para baixo.
3. Repita lentamente o movimento.',
'Melhora a mobilidade do antebraço, aumenta a flexibilidade e auxilia na recuperação funcional do braço.',
4
),

(
'Abrir e fechar as mãos',
'1. Fazer 15 repetições.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não apertar a mão com força excessiva.
2. Realizar os movimentos de forma confortável.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Sente-se confortavelmente.
2. Apoie os antebraços sobre uma mesa ou mantenha-os relaxados.
Movimento
1. Feche as mãos lentamente formando um punho.
2. Abra completamente os dedos.
3. Repita o movimento.',
'Melhora a mobilidade das mãos, reduz a rigidez dos dedos e auxilia na coordenação motora.',
5
),

(
'Alongamento dos dedos',
'1. Manter o alongamento por 15 segundos.
2. Repetir 3 vezes em cada mão.',
'1. Não puxar os dedos com força.
2. Alongar apenas até sentir leve tensão.
3. Suspender caso provoque dor intensa.',
'Posição inicial
1. Estenda um dos braços à frente.
Movimento
1. Com a outra mão, puxe delicadamente os dedos para trás.
2. Mantenha a posição.
3. Repita na outra mão.',
'Alonga os músculos e tendões das mãos, melhora a flexibilidade e reduz a rigidez articular.',
5
),

(
'Flexão e extensão dos punhos',
'1. Fazer 10 repetições.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Evitar movimentos rápidos.
2. Não ultrapassar o limite confortável.
3. Interromper caso haja dor intensa.',
'Posição inicial
1. Apoie o antebraço sobre uma mesa.
2. Deixe a mão para fora da borda.
Movimento
1. Dobre o punho para cima.
2. Em seguida, dobre para baixo.
3. Repita o movimento lentamente.',
'Melhora a mobilidade do punho, fortalece a musculatura da mão e auxilia na recuperação funcional.',
5
),

(
'Toque do polegar nos dedos',
'1. Fazer 10 repetições em cada mão.
2. Repetir 2 séries.',
'1. Executar lentamente.
2. Não forçar os dedos.
3. Parar caso sinta desconforto intenso.',
'Posição inicial
1. Mantenha a mão aberta.
Movimento
1. Toque o polegar na ponta do dedo indicador.
2. Em seguida, toque no dedo médio.
3. Continue até o dedo mínimo.
4. Repita o movimento.',
'Melhora a coordenação motora fina, aumenta a mobilidade dos dedos e favorece a destreza manual.',
5
),

(
'Apertar bola de borracha',
'1. Apertar a bola por 5 segundos.
2. Fazer 10 repetições.
3. Repetir 2 séries.',
'1. Utilizar uma bola macia.
2. Não apertar com força excessiva.
3. Interromper caso provoque dor intensa.',
'Posição inicial
1. Segure uma bola de borracha macia na mão.
Movimento
1. Aperte a bola lentamente.
2. Mantenha a contração por 5 segundos.
3. Relaxe a mão.
4. Repita o exercício.',
'Fortalece os músculos das mãos e dos dedos, melhora a força de preensão e auxilia na recuperação dos movimentos.',
5
),

(
'Alongamento peitoral na parede',
'1. Manter o alongamento por 20 segundos.
2. Repetir 3 vezes em cada lado.',
'1. Não forçar além do limite confortável.
2. Manter a respiração normal.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Fique ao lado de uma parede.
2. Apoie a palma da mão na parede com o braço estendido.
Movimento
1. Gire lentamente o tronco para o lado oposto.
2. Mantenha o alongamento.
3. Retorne devagar e repita do outro lado.',
'Alonga os músculos do peito, melhora a postura e reduz tensões musculares.',
6
),

(
'Abertura dos braços',
'1. Fazer 10 repetições.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não elevar os braços além do confortável.
2. Evitar movimentos bruscos.
3. Suspender caso haja dor intensa.',
'Posição inicial
1. Fique em pé com a coluna ereta.
Movimento
1. Estenda os braços à frente.
2. Abra os braços lentamente para os lados.
3. Retorne à posição inicial.
4. Repita o movimento.',
'Melhora a mobilidade dos ombros e do tórax, além de favorecer a flexibilidade da musculatura peitoral.',
6
),

(
'Alongamento com as mãos atrás das costas',
'1. Manter por 20 segundos.
2. Repetir 3 vezes.',
'1. Não forçar os ombros para trás.
2. Manter o movimento confortável.
3. Interromper caso provoque dor intensa.',
'Posição inicial
1. Fique em pé.
Movimento
1. Junte as mãos atrás das costas.
2. Estenda os braços suavemente.
3. Eleve levemente as mãos até sentir o alongamento.
4. Retorne lentamente.',
'Alonga a musculatura do peito e dos ombros, melhora a postura e reduz a rigidez muscular.',
6
),

(
'Respiração com expansão torácica',
'1. Fazer 10 respirações profundas.
2. Repetir 2 séries.',
'1. Não prender a respiração.
2. Executar em ritmo confortável.
3. Parar caso sinta tontura.',
'Posição inicial
1. Sente-se ou fique em pé com a coluna reta.
Movimento
1. Inspire profundamente pelo nariz expandindo o tórax.
2. Segure o ar por 2 segundos.
3. Expire lentamente pela boca.
4. Repita o exercício.',
'Melhora a mobilidade da caixa torácica, favorece a respiração e promove relaxamento muscular.',
6
),

(
'Alongamento dos braços acima da cabeça',
'1. Manter por 20 segundos.
2. Repetir 3 vezes.',
'1. Não arquear excessivamente a coluna.
2. Manter os ombros relaxados.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Fique em pé com os pés afastados na largura dos ombros.
Movimento
1. Entrelace os dedos das mãos.
2. Eleve os braços acima da cabeça.
3. Alongue suavemente para cima.
4. Retorne lentamente à posição inicial.',
'Promove o alongamento da musculatura peitoral, melhora a mobilidade dos ombros e contribui para uma postura mais adequada.',
6
),

(
'Contração abdominal',
'1. Fazer 10 repetições.
2. Manter a contração por 5 segundos.
3. Repetir 2 séries.',
'1. Não prender a respiração.
2. Manter a coluna em posição neutra.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Deite-se de barriga para cima com os joelhos dobrados.
2. Mantenha os pés apoiados no chão.
Movimento
1. Contraia o abdômen como se aproximasse o umbigo da coluna.
2. Mantenha a contração por 5 segundos.
3. Relaxe lentamente e repita.',
'Fortalece a musculatura abdominal profunda, melhora a estabilidade do tronco e auxilia na proteção da coluna.',
7
),

(
'Inclinação pélvica',
'1. Fazer 10 repetições.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não realizar movimentos bruscos.
2. Manter a respiração constante.
3. Suspender caso haja dor intensa.',
'Posição inicial
1. Deite-se de barriga para cima com os joelhos dobrados.
Movimento
1. Contraia o abdômen.
2. Pressione a região lombar contra o chão.
3. Retorne lentamente à posição inicial.
4. Repita o movimento.',
'Fortalece o abdômen e melhora o controle da pelve, contribuindo para a estabilidade da coluna.',
7
),

(
'Elevação alternada dos joelhos',
'1. Fazer 10 repetições com cada perna.
2. Repetir 2 séries.',
'1. Manter o abdômen contraído durante todo o exercício.
2. Não arquear a coluna.
3. Interromper caso sinta dor.',
'Posição inicial
1. Deite-se de barriga para cima.
2. Dobre os joelhos e mantenha os pés apoiados no chão.
Movimento
1. Eleve lentamente um joelho em direção ao peito.
2. Retorne à posição inicial.
3. Repita com a outra perna.',
'Fortalece o abdômen, melhora a coordenação motora e auxilia na estabilidade da região lombar.',
7
),

(
'Respiração diafragmática com contração abdominal',
'1. Fazer 10 respirações profundas.
2. Repetir 2 séries.',
'1. Não prender a respiração.
2. Executar o exercício lentamente.
3. Parar caso sinta tontura.',
'Posição inicial
1. Deite-se confortavelmente.
Movimento
1. Inspire profundamente pelo nariz.
2. Contraia levemente o abdômen.
3. Expire lentamente pela boca.
4. Relaxe e repita.',
'Melhora o controle da musculatura abdominal, favorece a respiração e promove relaxamento corporal.',
7
),

(
'Marcha estacionária deitada',
'1. Fazer 10 repetições com cada perna.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Manter a lombar apoiada no chão.
2. Não prender a respiração.
3. Suspender caso sinta dor intensa.',
'Posição inicial
1. Deite-se de barriga para cima com os joelhos dobrados.
Movimento
1. Eleve um pé do chão como se estivesse marchando.
2. Retorne lentamente.
3. Repita com a outra perna.
4. Alterne os lados até completar as repetições.',
'Fortalece a musculatura abdominal, melhora a estabilidade do tronco e auxilia no equilíbrio corporal.',
7
),

(
'Gato e vaca', 
'1. Fazer de 8 a 15 repetições lentamente
2. Respirar durante o movimento
3. Repetir de 1 a 3 séries', 
'1. Evitar caso sinta dor intensa durante o exercício
2. Pessoas com lesões graves na coluna devem ter orientação profissional
3. O movimento deve ser confortável e sem impacto', 
'Posição inicial
1. Fique de quatro apoios no chão.
2. Deixe as mãos alinhadas abaixo dos ombros.
3. Os joelhos devem ficar alinhados abaixo do quadril.
4. Mantenha os braços retos e o olhar para baixo.
Movimento “Gato”
1. Puxe o abdômen para dentro.
2. Arqueie as costas para cima.
3. Abaixe a cabeça olhando para o umbigo.
4. Segure por alguns segundos.
Movimento “Vaca”
1. Desça a barriga em direção ao chão lentamente.
2. Levante o peito e o olhar levemente para frente.
3. Deixe a coluna formar uma curva suave.
4. Segure por alguns segundos.',
'Alivia tensão nas costas e lombar, melhora a mobilidade da coluna, ajuda na postura, diminui rigidez muscular, promove relaxamento corporal e pode ajudar em desconfortos causados por muito tempo sentado.',
8),

(
'Ponte',
'1. Fazer 10 repetições.
2. Repetir de 2 a 3 séries.
3. Descansar 30 segundos entre as séries.',
'1. Não elevar o quadril além do confortável.
2. Manter os pés apoiados no chão.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Deite-se de barriga para cima.
2. Dobre os joelhos e mantenha os pés apoiados no chão.
3. Deixe os braços estendidos ao lado do corpo.
Movimento
1. Contraia o abdômen.
2. Eleve lentamente o quadril.
3. Mantenha a posição por 5 segundos.
4. Retorne lentamente à posição inicial.',
'Fortalece a musculatura da lombar, glúteos e abdômen, proporcionando maior estabilidade para a coluna e contribuindo para a melhora da postura.',
8
),

(
'Joelhos ao peito',
'1. Repetir 10 vezes.
2. Manter cada alongamento por 15 segundos.
3. Fazer 2 séries.',
'1. Não puxar os joelhos com força.
2. Manter a respiração normal.
3. Parar caso sinta dor intensa.',
'Posição inicial
1. Deite-se de barriga para cima.
Movimento
1. Leve os dois joelhos em direção ao peito.
2. Segure-os com as mãos.
3. Mantenha a posição.
4. Retorne lentamente.',
'Ajuda a aliviar a tensão na região lombar, melhora a flexibilidade da coluna e promove relaxamento muscular.',
8
),

(
'Rotação lombar deitado',
'1. Fazer 10 repetições para cada lado.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Manter os ombros apoiados no chão.
2. Não forçar a rotação.
3. Interromper caso provoque dor intensa.',
'Posição inicial
1. Deite-se de barriga para cima com os joelhos dobrados.
Movimento
1. Deixe os joelhos caírem lentamente para um lado.
2. Retorne ao centro.
3. Repita para o outro lado.',
'Melhora a mobilidade da coluna lombar, reduz a rigidez muscular e auxilia no alívio das dores nas costas.',
8
),

(
'Alongamento da coluna sentado',
'1. Manter por 20 segundos.
2. Repetir 3 vezes.',
'1. Não curvar excessivamente a coluna.
2. Executar lentamente.
3. Suspender caso haja dor intensa.',
'Posição inicial
1. Sente-se em uma cadeira com a coluna reta.
Movimento
1. Incline lentamente o tronco para frente.
2. Deixe os braços relaxados.
3. Mantenha a posição.
4. Retorne lentamente.',
'Alonga toda a coluna, reduz a tensão muscular e melhora a flexibilidade das costas.',
8
),

(
'Superman',
'1. Fazer 10 repetições.
2. Repetir 2 séries.
3. Manter cada repetição por 3 segundos.',
'1. Não elevar excessivamente os membros.
2. Manter os movimentos lentos.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Deite-se de barriga para baixo.
2. Estenda os braços à frente.
Movimento
1. Eleve simultaneamente os braços e as pernas alguns centímetros do chão.
2. Mantenha por 3 segundos.
3. Retorne lentamente à posição inicial.',
'Fortalece os músculos responsáveis pela estabilização da coluna, melhora a postura e auxilia na prevenção de dores lombares.',
8
),

(
'Abdução de quadril deitado',
'1. Fazer 10 repetições com cada perna.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não elevar a perna além do limite confortável.
2. Manter o tronco estável.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Deite-se de lado com as pernas estendidas.
2. Apoie a cabeça sobre o braço.
Movimento
1. Eleve lentamente a perna de cima.
2. Mantenha por 2 segundos.
3. Retorne devagar à posição inicial.
4. Repita com a outra perna.',
'Fortalece os músculos estabilizadores do quadril, melhora o equilíbrio e auxilia na prevenção de dores na região.',
9
),

(
'Extensão de quadril',
'1. Fazer 10 repetições com cada perna.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não arquear a coluna.
2. Manter o abdômen contraído.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Fique em quatro apoios.
Movimento
1. Estenda uma perna para trás.
2. Eleve-a levemente.
3. Retorne lentamente.
4. Repita com a outra perna.',
'Fortalece os glúteos e a musculatura posterior do quadril, contribuindo para maior estabilidade da pelve.',
9
),

(
'Alongamento do quadril',
'1. Manter por 20 segundos.
2. Repetir 3 vezes em cada lado.',
'1. Não forçar o alongamento.
2. Manter a respiração normal.
3. Suspender caso provoque dor intensa.',
'Posição inicial
1. Dê um passo à frente com uma das pernas.
2. Apoie o joelho da perna de trás no chão.
Movimento
1. Incline o tronco levemente para frente.
2. Mantenha o alongamento.
3. Retorne lentamente.
4. Repita do outro lado.',
'Alonga os músculos do quadril, melhora a flexibilidade e reduz a tensão muscular.',
9
),

(
'Elevação lateral da perna em pé',
'1. Fazer 10 repetições com cada perna.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Apoie-se em uma parede ou cadeira se necessário.
2. Não inclinar o tronco.
3. Parar caso sinta dor intensa.',
'Posição inicial
1. Fique em pé com a coluna ereta.
Movimento
1. Eleve lentamente uma perna para o lado.
2. Retorne devagar.
3. Repita o movimento.
4. Alterne as pernas.',
'Fortalece a musculatura lateral do quadril, melhora o equilíbrio e auxilia na estabilidade durante a caminhada.',
9
),

(
'Rotação externa do quadril sentado',
'1. Fazer 10 repetições com cada perna.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não forçar a articulação.
2. Manter a postura correta.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Sente-se em uma cadeira com os pés apoiados no chão.
Movimento
1. Gire lentamente o joelho para fora.
2. Retorne ao centro.
3. Repita o movimento.
4. Alterne as pernas.',
'Melhora a mobilidade da articulação do quadril, reduz a rigidez e favorece a execução das atividades diárias.',
9
),


(
'Elevação da perna estendida',
'1. Fazer 10 repetições com cada perna.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não arquear a coluna durante o exercício.
2. Manter os movimentos controlados.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Deite-se de barriga para cima.
2. Mantenha uma perna dobrada e a outra estendida.
Movimento
1. Eleve lentamente a perna estendida.
2. Mantenha por 2 segundos.
3. Retorne devagar.
4. Repita com a outra perna.',
'Fortalece os músculos da parte anterior da coxa, melhora a estabilidade do joelho e auxilia na recuperação funcional.',
10
),

(
'Alongamento da parte posterior da coxa',
'1. Manter o alongamento por 20 segundos.
2. Repetir 3 vezes em cada perna.',
'1. Não forçar além do limite confortável.
2. Manter a coluna alinhada.
3. Suspender caso haja dor intensa.',
'Posição inicial
1. Sente-se no chão com uma perna estendida.
Movimento
1. Incline o tronco lentamente em direção ao pé.
2. Mantenha a posição.
3. Retorne devagar.
4. Repita com a outra perna.',
'Alonga a musculatura posterior da coxa, melhora a flexibilidade e reduz a tensão muscular.',
10
),

(
'Agachamento parcial',
'1. Fazer 10 repetições.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não ultrapassar o limite confortável.
2. Manter os joelhos alinhados com os pés.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Fique em pé com os pés afastados na largura dos ombros.
Movimento
1. Flexione levemente os joelhos como se fosse sentar.
2. Retorne lentamente à posição inicial.
3. Repita o movimento.',
'Fortalece a musculatura da coxa e melhora a estabilidade dos membros inferiores.',
10
),

(
'Extensão de joelho sentado',
'1. Fazer 10 repetições com cada perna.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não estender o joelho com rapidez.
2. Manter a postura correta.
3. Parar caso sinta dor intensa.',
'Posição inicial
1. Sente-se em uma cadeira.
Movimento
1. Estenda lentamente uma perna até alinhá-la.
2. Mantenha por 3 segundos.
3. Retorne lentamente.
4. Alterne as pernas.',
'Fortalece a musculatura da parte anterior da coxa, melhora a mobilidade do joelho e auxilia na recuperação funcional.',
10
),

(
'Flexão de joelho em pé',
'1. Fazer 10 repetições com cada perna.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Apoie-se em uma parede ou cadeira se necessário.
2. Não movimentar o quadril durante o exercício.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Fique em pé com a coluna ereta.
Movimento
1. Dobre lentamente o joelho levando o calcanhar em direção ao glúteo.
2. Retorne devagar.
3. Repita com a outra perna.',
'Fortalece a musculatura posterior da coxa, melhora a mobilidade e contribui para a estabilidade dos membros inferiores.',
10
),

(
'Elevação da perna reta',
'1. Fazer 10 repetições com cada perna.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Manter a perna estendida durante o movimento.
2. Não arquear a coluna.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Deite-se de barriga para cima.
2. Dobre uma perna e mantenha a outra estendida.
Movimento
1. Eleve lentamente a perna estendida até aproximadamente 45 graus.
2. Mantenha por 3 segundos.
3. Retorne lentamente.
4. Repita com a outra perna.',
'Fortalece a musculatura da coxa, melhora a estabilidade do joelho e auxilia na recuperação funcional.',
11
),

(
'Extensão de joelho com toalha',
'1. Fazer 10 repetições.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não forçar a extensão completa caso haja dor.
2. Manter o movimento controlado.
3. Suspender caso provoque desconforto intenso.',
'Posição inicial
1. Sente-se em uma cadeira.
2. Coloque uma toalha enrolada sob o joelho.
Movimento
1. Estenda lentamente a perna.
2. Mantenha por 5 segundos.
3. Retorne devagar à posição inicial.',
'Melhora a mobilidade do joelho, fortalece o quadríceps e auxilia na recuperação dos movimentos.',
11
),

(
'Agachamento na parede',
'1. Fazer 10 repetições.
2. Repetir 2 séries.
3. Descansar 30 segundos entre as séries.',
'1. Não flexionar os joelhos além do confortável.
2. Manter as costas apoiadas na parede.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Fique de costas para uma parede.
2. Apoie completamente as costas.
Movimento
1. Deslize lentamente para baixo até uma leve flexão dos joelhos.
2. Mantenha por 5 segundos.
3. Retorne lentamente.',
'Fortalece os músculos que estabilizam o joelho, melhora o equilíbrio e auxilia na prevenção de lesões.',
11
),

(
'Flexão de joelho sentado',
'1. Fazer 10 repetições com cada perna.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Manter a postura correta.
2. Não realizar movimentos bruscos.
3. Parar caso sinta dor intensa.',
'Posição inicial
1. Sente-se em uma cadeira.
Movimento
1. Dobre lentamente o joelho levando o pé para trás.
2. Retorne à posição inicial.
3. Repita com a outra perna.',
'Melhora a mobilidade da articulação do joelho, reduz a rigidez e favorece a recuperação funcional.',
11
),

(
'Alongamento da panturrilha',
'1. Manter por 20 segundos.
2. Repetir 3 vezes em cada perna.',
'1. Não forçar o alongamento.
2. Manter o calcanhar apoiado no chão.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Fique de frente para uma parede.
2. Apoie as mãos na parede.
Movimento
1. Leve uma perna para trás mantendo-a estendida.
2. Flexione levemente a perna da frente.
3. Mantenha o alongamento.
4. Repita com a outra perna.',
'Melhora a flexibilidade da panturrilha, reduz a tensão muscular e contribui para a mobilidade e estabilidade do joelho.',
11
),

(
'Flexão dorsal do tornozelo',
'1. Fazer 15 repetições com cada pé.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não forçar o movimento além do confortável.
2. Manter os movimentos controlados.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Sente-se em uma cadeira com os pés apoiados no chão.
Movimento
1. Levante a ponta do pé em direção ao corpo.
2. Mantenha por 2 segundos.
3. Retorne lentamente.
4. Repita com o outro pé.',
'Fortalece a musculatura da parte anterior da perna, melhora a mobilidade do tornozelo e auxilia na prevenção de lesões.',
12
),

(
'Flexão plantar do tornozelo',
'1. Fazer 15 repetições com cada pé.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não realizar movimentos bruscos.
2. Manter a postura correta.
3. Suspender caso provoque dor intensa.',
'Posição inicial
1. Sente-se confortavelmente.
Movimento
1. Empurre a ponta do pé para baixo.
2. Mantenha por 2 segundos.
3. Retorne lentamente.
4. Repita o movimento.',
'Fortalece a musculatura da panturrilha e melhora a mobilidade do tornozelo.',
12
),

(
'Elevação dos calcanhares',
'1. Fazer 15 repetições.
2. Repetir 2 séries.
3. Descansar 30 segundos entre as séries.',
'1. Apoie-se em uma parede ou cadeira se necessário.
2. Executar lentamente.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Fique em pé com os pés paralelos.
Movimento
1. Eleve os calcanhares ficando na ponta dos pés.
2. Mantenha por 3 segundos.
3. Retorne lentamente à posição inicial.',
'Fortalece a musculatura da panturrilha, melhora o equilíbrio e auxilia na estabilidade dos membros inferiores.',
12
),

(
'Alongamento da panturrilha na parede',
'1. Manter por 20 segundos.
2. Repetir 3 vezes em cada perna.',
'1. Não forçar o alongamento.
2. Manter o calcanhar apoiado no chão.
3. Parar caso sinta dor intensa.',
'Posição inicial
1. Fique de frente para uma parede.
2. Apoie as mãos na parede.
Movimento
1. Leve uma perna para trás mantendo-a estendida.
2. Flexione levemente a perna da frente.
3. Mantenha o alongamento.
4. Repita com a outra perna.',
'Alonga a musculatura da panturrilha, melhora a flexibilidade e reduz a tensão muscular da perna.',
12
),

(
'Movimentos circulares com o tornozelo',
'1. Fazer 10 rotações para cada lado.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não realizar movimentos rápidos.
2. Respeitar o limite de movimento.
3. Interromper caso provoque dor intensa.',
'Posição inicial
1. Sente-se em uma cadeira.
2. Mantenha um pé suspenso.
Movimento
1. Faça movimentos circulares com o tornozelo.
2. Complete as rotações em um sentido.
3. Inverta o sentido e repita.
4. Alterne os pés.',
'Melhora a mobilidade do tornozelo, aumenta a flexibilidade e contribui para a prevenção de lesões.',
12
),

(
'Flexão e extensão dos dedos dos pés',
'1. Fazer 15 repetições.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Não forçar os dedos além do limite confortável.
2. Manter os movimentos suaves.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Sente-se em uma cadeira com os pés apoiados no chão.
Movimento
1. Dobre os dedos dos pés.
2. Em seguida, estenda-os completamente.
3. Repita o movimento lentamente.',
'Melhora a mobilidade dos dedos dos pés, fortalece a musculatura e contribui para uma melhor estabilidade durante a caminhada.',
13
),

(
'Elevação dos dedos dos pés',
'1. Fazer 15 repetições.
2. Repetir 2 séries.
3. Executar lentamente.',
'1. Manter os calcanhares apoiados no chão.
2. Não realizar movimentos bruscos.
3. Suspender caso provoque dor intensa.',
'Posição inicial
1. Sente-se confortavelmente com os pés apoiados.
Movimento
1. Levante apenas os dedos dos pés.
2. Mantenha por 2 segundos.
3. Retorne lentamente à posição inicial.',
'Fortalece a musculatura dos pés, melhora o controle dos movimentos e auxilia na estabilidade dos membros inferiores.',
13
),

(
'Enrolar uma toalha com os pés',
'1. Repetir 10 vezes com cada pé.
2. Fazer 2 séries.
3. Executar lentamente.',
'1. Utilizar uma toalha leve.
2. Não realizar movimentos rápidos.
3. Interromper caso sinta dor intensa.',
'Posição inicial
1. Coloque uma toalha estendida no chão.
2. Sente-se em uma cadeira.
Movimento
1. Utilize os dedos dos pés para puxar a toalha em sua direção.
2. Relaxe os pés.
3. Repita o movimento.',
'Fortalece a musculatura dos pés, melhora a coordenação motora e auxilia na recuperação funcional.',
13
),

(
'Alongamento da fáscia plantar',
'1. Manter por 20 segundos.
2. Repetir 3 vezes em cada pé.',
'1. Não forçar o alongamento.
2. Executar o movimento lentamente.
3. Suspender caso sinta dor intensa.',
'Posição inicial
1. Sente-se em uma cadeira.
2. Cruze uma perna sobre a outra.
Movimento
1. Segure os dedos do pé.
2. Puxe-os suavemente em direção à canela.
3. Mantenha o alongamento.
4. Retorne lentamente.',
'Alonga a região da planta do pé, melhora a flexibilidade e auxilia no alívio de tensões e desconfortos.',
13
),

(
'Equilíbrio em um pé',
'1. Permanecer por 20 segundos.
2. Repetir 3 vezes com cada perna.',
'1. Apoie-se em uma parede ou cadeira caso necessário.
2. Não realizar o exercício em superfícies instáveis.
3. Interromper caso sinta tontura ou perda de equilíbrio.',
'Posição inicial
1. Fique em pé com os pés paralelos.
Movimento
1. Eleve um dos pés do chão.
2. Mantenha o equilíbrio pelo tempo indicado.
3. Retorne lentamente.
4. Repita com a outra perna.',
'Fortalece a musculatura dos pés e tornozelos, melhora o equilíbrio e contribui para a prevenção de quedas.',
13
);

insert into exercicio (titulo, repeticao, cuidados, como_fazer, descricao, id_regiao_corpo) values
(
'Massagem nas têmporas',
'1. Repetir por 2 a 3 minutos.
2. Fazer movimentos circulares lentamente.
3. Respirar profundamente durante o exercício.',
'1. Não pressionar com força excessiva.
2. Interromper caso a dor aumente.
3. Evitar realizar sobre áreas lesionadas.',
'Posição inicial
1. Sente-se confortavelmente.
2. Relaxe os ombros.
Movimento
1. Coloque as pontas dos dedos nas têmporas.
2. Faça movimentos circulares suaves.
3. Continue por alguns minutos mantendo a respiração tranquila.',
'Ajuda a aliviar dores de cabeça causadas por tensão muscular, promove relaxamento e melhora a circulação da região.',
1
),

(
'Relaxamento da mandíbula',
'1. Repetir 10 vezes.
2. Fazer o movimento lentamente.
3. Descansar por 30 segundos após cada série.',
'1. Não forçar a abertura da boca.
2. Manter o movimento confortável.
3. Parar caso sinta dor intensa.',
'Posição inicial
1. Sente-se com a coluna ereta.
Movimento
1. Abra lentamente a boca.
2. Mantenha por 3 segundos.
3. Feche lentamente.
4. Relaxe a musculatura da face.',
'Reduz a tensão da mandíbula, auxilia no alívio de dores de cabeça tensionais e melhora o relaxamento facial.',
1
),

(
'Respiração diafragmática',
'1. Respirar profundamente por 5 minutos.
2. Repetir diariamente.',
'1. Evitar prender a respiração.
2. Realizar em ambiente tranquilo.
3. Manter postura confortável.',
'Posição inicial
1. Sente-se ou deite-se confortavelmente.
Movimento
1. Inspire lentamente pelo nariz.
2. Expanda o abdômen.
3. Expire devagar pela boca.
4. Repita continuamente.',
'Promove relaxamento, reduz o estresse e pode diminuir dores de cabeça relacionadas à tensão emocional.',
1
),

(
'Relaxamento dos músculos da face',
'1. Repetir 10 vezes.
2. Manter cada contração por 5 segundos.',
'1. Não realizar movimentos bruscos.
2. Relaxar entre cada repetição.
3. Interromper caso haja desconforto.',
'Posição inicial
1. Sente-se confortavelmente.
Movimento
1. Feche os olhos suavemente.
2. Contraia levemente os músculos da face.
3. Relaxe completamente.
4. Repita o movimento.',
'Ajuda a aliviar a tensão muscular da face, favorece o relaxamento e contribui para diminuir dores de cabeça tensionais.',
1
),

(
'Automassagem no couro cabeludo',
'1. Realizar por 3 a 5 minutos.
2. Fazer movimentos suaves e contínuos.',
'1. Não utilizar as unhas.
2. Evitar pressão excessiva.
3. Suspender caso provoque desconforto.',
'Posição inicial
1. Sente-se confortavelmente.
Movimento
1. Posicione as pontas dos dedos sobre o couro cabeludo.
2. Faça movimentos circulares suaves.
3. Percorra toda a cabeça lentamente.',
'Estimula a circulação sanguínea, promove relaxamento e auxilia na redução da tensão muscular da cabeça.',
1
);

insert into cronograma (dia, id_exercicio, id_regiao_corpo) values
(1,62,1),
(1,63,1),
(1,64,1),
(2,63,1),
(2,65,1),
(2,66,1),
(3,62,1),
(3,64,1),
(3,66,1),
(4,64,1),
(4,62,1),
(4,63,1),
(5,66,1),
(5,64,1),
(5,65,1),
(6,63,1),
(6,66,1),
(6,62,1),
(7,64,1),
(7,65,1),
(7,63,1),
(1,1,2),
(1,2,2),
(1,3,2),
(2,4,2),
(2,5,2),
(2,1,2),
(3,3,2),
(3,5,2),
(3,2,2),
(4,4,2),
(4,5,2),
(4,3,2),
(5,4,2),
(5,1,2),
(5,2,2),
(6,4,2),
(6,5,2),
(6,1,2),
(7,3,2),
(7,4,2),
(7,2,2),
(1,6,3),
(1,7,3),
(1,8,3),
(2,9,3),
(2,10,3),
(2,6,3),
(3,7,3),
(3,8,3),
(3,9,3),
(4,10,3),
(4,6,3),
(4,7,3),
(5,10,3),
(5,8,3),
(5,9,3),
(6,10,3),
(6,6,3),
(6,7,3),
(7,10,3),
(7,8,3),
(7,9,3),
(1,11,4),
(1,12,4),
(1,13,4),
(2,15,4),
(2,14,4),
(2,12,4),
(3,15,4),
(3,11,4),
(3,14,4),
(4,13,4),
(4,12,4),
(4,15,4),
(5,12,4),
(5,11,4),
(5,13,4),
(6,15,4),
(6,14,4),
(6,13,4),
(7,18,4),
(7,14,4),
(7,12,4),
(1,16,5),
(1,17,5),
(1,18,5),
(2,19,5),
(2,20,5),
(2,17,5),
(3,19,5),
(3,16,5),
(3,18,5),
(4,20,5),
(4,19,5),
(4,16,5),
(5,18,5),
(5,20,5),
(5,17,5),
(6,19,5),
(6,18,5),
(6,20,5),
(7,17,5),
(7,16,5),
(7,18,5),
(1,21,6),
(1,22,6),
(1,23,6),
(2,24,6),
(2,25,6),
(2,21,6),
(3,22,6),
(3,23,6),
(3,25,6),
(4,24,6),
(4,25,6),
(4,21,6),
(5,21,6),
(5,23,6),
(5,25,6),
(6,22,6),
(6,24,6),
(6,23,6),
(7,21,6),
(7,22,6),
(7,25,6),
(1,26,7),
(1,27,7),
(1,28,7),
(2,29,7),
(2,30,7),
(2,27,7),
(3,26,7),
(3,28,7),
(3,30,7),
(4,27,7),
(4,29,7),
(4,26,7),
(5,26,7),
(5,27,7),
(5,28,7),
(6,29,7),
(6,30,7),
(6,28,7),
(7,26,7),
(7,27,7),
(7,30,7),
(1,31,8),
(1,32,8),
(1,33,8),
(2,34,8),
(2,35,8),
(2,36,8),
(3,31,8),
(3,33,8),
(3,35,8),
(4,32,8),
(4,34,8),
(4,36,8),
(5,31,8),
(5,34,8),
(5,35,8),
(6,32,8),
(6,33,8),
(6,36,8),
(7,31,8),
(7,35,8),
(7,36,8),
(1,37,9),
(1,38,9),
(1,39,9),
(2,40,9),
(2,41,9),
(2,39,9),
(3,37,9),
(3,39,9),
(3,41,9),
(4,38,9),
(4,40,9),
(4,37,9),
(5,40,9),
(5,39,9),
(5,41,9),
(6,37,9),
(6,38,9),
(6,41,9),
(7,39,9),
(7,40,9),
(7,38,9),
(1,42,10),
(1,43,10),
(1,44,10),
(2,45,10),
(2,46,10),
(2,43,10),
(3,42,10),
(3,44,10),
(3,46,10),
(4,43,10),
(4,45,10),
(4,42,10),
(5,44,10),
(5,46,10),
(5,43,10),
(6,42,10),
(6,44,10),
(6,46,10),
(7,42,10),
(7,43,10),
(7,45,10),
(1,47,11),
(1,48,11),
(1,49,11),
(2,50,11),
(2,51,11),
(2,47,11),
(3,49,11),
(3,51,11),
(3,48,11),
(4,50,11),
(4,49,11),
(4,47,11),
(5,51,11),
(5,48,11),
(5,50,11),
(6,47,11),
(6,49,11),
(6,48,11),
(7,51,11),
(7,50,11),
(7,47,11),
(1,52,12),
(1,53,12),
(1,54,12),
(2,55,12),
(2,56,12),
(2,52,12),
(3,53,12),
(3,54,12),
(3,55,12),
(4,56,12),
(4,52,12),
(4,53,12),
(5,54,12),
(5,55,12),
(5,56,12),
(6,52,12),
(6,53,12),
(6,54,12),
(7,55,12),
(7,56,12),
(7,52,12),
(1,57,13),
(1,58,13),
(1,59,13),
(2,60,13),
(2,61,13),
(2,57,13),
(3,58,13),
(3,59,13),
(3,60,13),
(4,61,13),
(4,57,13),
(4,58,13),
(5,59,13),
(5,60,13),
(5,61,13),
(6,57,13),
(6,58,13),
(6,59,13),
(7,60,13),
(7,61,13),
(7,57,13);

ALTER TABLE perfil
ADD COLUMN altura DECIMAL(5,2) NULL AFTER peso;

ALTER TABLE perfil
ADD COLUMN altura DECIMAL(5,2) NULL AFTER peso;
 
-- permite usar "INSERT ... ON DUPLICATE KEY UPDATE" ao sincronizar dores
ALTER TABLE registro_dor
ADD UNIQUE KEY uq_usuario_regiao (id_usuario, id_regiao_corpo);
