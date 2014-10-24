library ieee;
	use ieee.std_logic_1164.all;
--	use ieee.numeric_std.all;

entity addsub is
	generic (
		NBITS: natural     := 8;
		NPIPELINE : natural := 0; -- 0 pour aucun registre interne, 1 pour 1 registre interne, 2 pour 1+2=3 registres internes, ... N pour 2**N-1 registres internes.
		ISRESET: std_logic := '0'
	);
	port (
		clk, rst, sub : in  std_logic;
		A, B          : in  std_logic_vector(NBITS-1 downto 0);
		Sr            : out std_logic_vector(NBITS-1 downto 0) -- output registered
	);
	
	
	function cNREGSBEFORE(constant i : natural)
		return natural is
			variable pas   : natural;
			variable r     : natural:=0;
			variable index : natural :=0;
		begin
			if NPIPELINE=0 then
				return r;
			else
				pas:=NBITS/(2*NPIPELINE);
				for j in 0 to i loop
					if index = pas then
						r := r + 1;
						index := 0;
					end if;
					index := index + 1;
				end loop;
				return r;
			end if;
	end function cNREGSBEFORE;
	
	function cNREGSAFTER(constant i : natural)
		return natural is
		begin
			return 2**NPIPELINE-1-cNREGSBEFORE(i);
	end function cNREGSAFTER;
	
	function cISCREG(constant i : natural)
		return natural is
			variable pas   : natural;
			variable r     : natural:=0;
			variable index : natural :=0;
		begin
			if NPIPELINE=0 then
				return r;
			else
				pas:=NBITS/(2*NPIPELINE);
				for j in 0 to i loop
					if index = pas then
						r := 1;
						index := 0;
					else
						r := 0;
					end if;
					index := index + 1;
				end loop;
				return r;
			end if;
	end function cISCREG;
	
end entity addsub;

architecture rtl of addsub is
	signal Ar, Br, S, C, Bc: std_logic_vector(NBITS-1 downto 0);
	signal subr : std_logic;
	component fullAdderWithRegisters
		generic (
			NREGSBEFORE: natural   :=  0;
			NREGSAFTER : natural   :=  0;
			ISCREG     : natural   :=  0;
			ISRESET    : std_logic := '0'
		);
		port (
			clk, rst : in  std_logic;
			a, b, c  : in  std_logic;
			r, s     : out std_logic
		);
	end component fullAdderWithRegisters;
begin
	process (rst, clk)
	begin
		if ISRESET= '1' and rst = '1' then
			Ar <= (others => '0'); -- A and B are registered before calculus
			Br <= (others => '0');
			Sr <= (others => '0');
			subr <= '0';
		elsif clk'event and clk = '1' then
			Ar <= a;
			Br <= b;
			Sr <= S;
			subr <= sub;
		end if;
	end process;
	C(0) <= subr;
	G1: for i in 0 to NBITS-1 generate
		Bc(i) <= Br(i) xor subr;
		G2: if i < NBITS-1 generate
			inst: fullAdderWithRegisters
				generic map(NREGSBEFORE=>cNREGSBEFORE(i), NREGSAFTER=>cNREGSAFTER(i), ISCREG=>cISCREG(i), ISRESET=>ISRESET)
				port map(clk=>clk, rst=>rst, a=>Ar(i), b=>Bc(i), c=>C(i), r=>C(i+1), s=>S(i));
		end generate G2;
		G3: if i = NBITS-1 generate
			inst: fullAdderWithRegisters
				generic map(NREGSBEFORE=>cNREGSBEFORE(i), NREGSAFTER=>cNREGSAFTER(i), ISCREG=>cISCREG(i), ISRESET=>ISRESET)
				port map(clk=>clk, rst=>rst, a=>Ar(i), b=>Bc(i), c=>C(i), r=>open  , s=>S(i));
		end generate G3;
	end generate G1;
end rtl;



